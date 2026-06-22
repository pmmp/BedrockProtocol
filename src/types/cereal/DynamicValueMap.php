<?php

/*
 *
 *  ____            _        _   __  __ _                  __  __ ____
 * |  _ \ ___   ___| | _____| |_|  \/  (_)_ __   ___      |  \/  |  _ \
 * | |_) / _ \ / __| |/ / _ \ __| |\/| | | '_ \ / _ \_____| |\/| | |_) |
 * |  __/ (_) | (__|   <  __/ |_| |  | | | | | |  __/_____| |  | |  __/
 * |_|   \___/ \___|_|\_\___|\__|_|  |_|_|_| |_|\___|     |_|  |_|_|
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * @author PocketMine Team
 * @link http://www.pocketmine.net/
 *
 *
 */

declare(strict_types=1);

namespace pocketmine\network\mcpe\protocol\types\cereal;

use pmmp\encoding\ByteBufferReader;
use pmmp\encoding\ByteBufferWriter;
use pmmp\encoding\VarInt;
use pocketmine\network\mcpe\protocol\serializer\CommonTypes;
use pocketmine\network\mcpe\protocol\types\cereal\DynamicValue;
use pocketmine\network\mcpe\protocol\types\GetTypeIdFromConstTrait;

final class DynamicValueMap extends DynamicValue{
	use GetTypeIdFromConstTrait;

	public const ID = DynamicValueType::MAP;

	/**
	 * @param DynamicValue[] $value
	 * @phpstan-param array<string, DynamicValue> $value
	 */
	public function __construct(
		private array $value
	){}

	/**
	 * @return DynamicValue[]
	 * @phpstan-return array<string, DynamicValue>
	 */
	public function getValue() : array{ return $this->value; }

	protected static function readValue(ByteBufferReader $in) : self{
		$value = [];

		for($i = 0, $count = VarInt::readUnsignedInt($in); $i < $count; $i++){
			$key = CommonTypes::getString($in);
			//YIKES! unchecked recursion ?!?!?! thank god this never gets sent by the client...
			$value[$key] = DynamicValue::read($in);
		}

		return new self($value);
	}

	protected function writeValue(ByteBufferWriter $out) : void{
		VarInt::writeUnsignedInt($out, count($this->value));
		foreach($this->value as $key => $value){
			CommonTypes::putString($out, $key);
			$value->write($out);
		}
	}
}