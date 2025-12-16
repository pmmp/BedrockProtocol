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
use pmmp\encoding\VarInt;
use pocketmine\network\mcpe\protocol\PacketDecodeException;
use pocketmine\network\mcpe\protocol\serializer\CommonTypes;

/**
 * @see ClientboundDataStorePacket
 */
final class DataStoreChange extends DataStore {
	public const ID = DataStoreType::CHANGE;

	public function __construct(
		private string $name,
		private string $property,
		private int $updateCount,
		private DataStoreValue $data
	){}

	public function getTypeId() : int{
		return self::ID;
	}

	public function getName() : string{ return $this->name; }

	public function getProperty() : string{ return $this->property; }

	public function getUpdateCount() : int{ return $this->updateCount; }

	public function getData() : DataStoreValue{ return $this->data; }

	public static function read(ByteBufferReader $in) : self{
		$name = CommonTypes::getString($in);
		$property = CommonTypes::getString($in);
		$updateCount = VarInt::readUnsignedInt($in);

		$data = match(VarInt::readUnsignedInt($in)){
			DataStoreValueType::DOUBLE => DoubleDataStoreValue::read($in),
			DataStoreValueType::BOOL => BoolDataStoreValue::read($in),
			DataStoreValueType::STRING => StringDataStoreValue::read($in),
			default => throw new PacketDecodeException("Unknown DataStoreValueType"),
		};

		return new self(
			$name,
			$property,
			$updateCount,
			$data,
		);
	}

	public function write(ByteBufferWriter $out) : void{
		CommonTypes::putString($out, $this->name);
		CommonTypes::putString($out, $this->property);
		VarInt::writeUnsignedInt($out, $this->updateCount);
		VarInt::writeUnsignedInt($out, $this->data->getTypeId());
		$this->data->write($out);
	}
}
