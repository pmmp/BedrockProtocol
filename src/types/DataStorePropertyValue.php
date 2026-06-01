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

namespace pocketmine\network\mcpe\protocol\types;

use pmmp\encoding\ByteBufferReader;
use pmmp\encoding\ByteBufferWriter;
use pmmp\encoding\LE;
use pocketmine\network\mcpe\protocol\PacketDecodeException;

abstract class DataStorePropertyValue{

	abstract public function getTypeId() : int;

	final public static function read(ByteBufferReader $in) : self{
		return match(LE::readSignedInt($in)){
			DataStorePropertyType::NONE => NoneDataStorePropertyValue::readPayload($in),
			DataStorePropertyType::BOOL => BoolDataStorePropertyValue::readPayload($in),
			DataStorePropertyType::INT64 => Int64DataStorePropertyValue::readPayload($in),
			DataStorePropertyType::STRING => StringDataStorePropertyValue::readPayload($in),
			DataStorePropertyType::MAP => MapDataStorePropertyValue::readPayload($in),
			default => throw new PacketDecodeException("Unknown DataStorePropertyType"),
		};
	}

	final public function writeWithType(ByteBufferWriter $out) : void{
		LE::writeSignedInt($out, $this->getTypeId());
		$this->write($out);
	}

	abstract public function write(ByteBufferWriter $out) : void;
}
