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

abstract class DynamicValue{
	abstract public function getTypeId() : int;

	abstract protected function writeValue(ByteBufferWriter $out) : void;

	final public function write(ByteBufferWriter $out) : void{
		$this->writeValue($out);
	}

	final public static function read(ByteBufferReader $in, int $type) : ?self{
		//TODO: I don't like putting this here (cyclic dependency) but I don't know where else to put it for now.
		//Really we need to revamp how unions are handled in general, but that's a job for another time
		return match($type){
			DynamicValueType::NULL => null,
			DynamicValueBool::ID => DynamicValueBool::readValue($in),
			DynamicValueLong::ID => DynamicValueLong::readValue($in),
			DynamicValueDouble::ID => DynamicValueDouble::readValue($in),
			DynamicValueString::ID => DynamicValueString::readValue($in),
			DynamicValueList::ID => DynamicValueList::readValue($in),
			DynamicValueMap::ID => DynamicValueMap::readValue($in),
		};
	}
}