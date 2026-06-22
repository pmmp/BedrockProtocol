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
use pocketmine\network\mcpe\protocol\types\GetTypeIdFromConstTrait;
use function count;

final class DynamicValueList extends DynamicValue{
	use GetTypeIdFromConstTrait;

	public const ID = DynamicValueType::LIST;

	/**
	 * @param (DynamicValue|null)[] $values
	 * @phpstan-param list<DynamicValue|null> $values
	 */
	public function __construct(
		private array $values
	){}

	/**
	 * @return (DynamicValue|null)[]
	 * @phpstan-return list<DynamicValue|null>
	 */
	public function getValues() : array{
		return $this->values;
	}

	protected static function readValue(ByteBufferReader $in) : self{
		$size = VarInt::readUnsignedInt($in);
		$values = [];
		for($i = 0; $i < $size; ++$i){
			$type = LE::readUnsignedInt($in);
			$values[] = DynamicValue::read($in, $type);
		}
		return new self($values);
	}

	protected function writeValue(ByteBufferWriter $out) : void{
		VarInt::writeUnsignedInt($out, count($this->values));
		foreach($this->values as $value){
			LE::writeUnsignedInt($out, $value?->getTypeId() ?? DynamicValueType::NULL);
			$value?->write($out);
		}
	}
}
