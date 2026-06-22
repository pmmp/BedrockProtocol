<?php

/*
 * This file is part of BedrockProtocol.
 * Copyright (C) 2014-2022 PocketMine Team <https://github.com/pmmp/BedrockProtocol>
 *
 * BedrockProtocol is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 */

declare(strict_types=1);

namespace pocketmine\network\mcpe\protocol\types\cereal;

use pmmp\encoding\ByteBufferReader;
use pmmp\encoding\ByteBufferWriter;
use pmmp\encoding\LE;
use pmmp\encoding\VarInt;
use pocketmine\network\mcpe\protocol\serializer\CommonTypes;
use pocketmine\network\mcpe\protocol\types\GetTypeIdFromConstTrait;
use function count;

final class DynamicValueMap extends DynamicValue{
	use GetTypeIdFromConstTrait;

	public const ID = DynamicValueType::MAP;

	/**
	 * @param (DynamicValue|null)[] $value
	 * @phpstan-param array<string, DynamicValue|null> $value
	 */
	public function __construct(
		private array $value
	){}

	/**
	 * @return (DynamicValue|null)[]
	 * @phpstan-return array<string, DynamicValue|null>
	 */
	public function getValue() : array{ return $this->value; }

	protected static function readValue(ByteBufferReader $in) : self{
		$value = [];

		for($i = 0, $count = VarInt::readUnsignedInt($in); $i < $count; $i++){
			$key = CommonTypes::getString($in);
			//YIKES! unchecked recursion ?!?!?! thank god this never gets sent by the client...
			$type = LE::readUnsignedInt($in);
			$value[$key] = DynamicValue::read($in, $type);
		}

		return new self($value);
	}

	protected function writeValue(ByteBufferWriter $out) : void{
		VarInt::writeUnsignedInt($out, count($this->value));
		foreach($this->value as $key => $value){
			CommonTypes::putString($out, (string) $key); //make sure we don't get any unexpected strings casted to int
			LE::writeUnsignedInt($out, $value?->getTypeId() ?? DynamicValueType::NULL);
			$value?->write($out);
		}
	}
}
