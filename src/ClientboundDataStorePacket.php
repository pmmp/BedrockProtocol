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

namespace pocketmine\network\mcpe\protocol;

use pmmp\encoding\ByteBufferReader;
use pmmp\encoding\ByteBufferWriter;
use pmmp\encoding\VarInt;
use pocketmine\network\mcpe\protocol\types\DataStore;
use pocketmine\network\mcpe\protocol\types\DataStoreChange;
use pocketmine\network\mcpe\protocol\types\DataStoreRemoval;
use pocketmine\network\mcpe\protocol\types\DataStoreType;
use pocketmine\network\mcpe\protocol\types\DataStoreUpdate;
use function count;

class ClientboundDataStorePacket extends DataPacket{
	public const NETWORK_ID = ProtocolInfo::CLIENTBOUND_DATA_STORE_PACKET;

	/**
	 * @var DataStore[]
	 * @phpstan-var list<DataStore>
	 */
	public array $values = [];

	/**
	 * @generate-create-func
	 * @param DataStore[] $values
	 * @phpstan-param list<DataStore> $values
	 */
	public static function create(array $values) : self{
		$result = new self;
		$result->values = $values;
		return $result;
	}

	protected function decodePayload(ByteBufferReader $in) : void{
		$this->values = [];
		for($i = 0, $len = VarInt::readUnsignedInt($in); $i < $len; ++$i){
			$this->values[] = match(VarInt::readUnsignedInt($in)){
				DataStoreType::UPDATE => DataStoreUpdate::read($in),
				DataStoreType::CHANGE => DataStoreChange::read($in),
				DataStoreType::REMOVAL => DataStoreRemoval::read($in),
				default => throw new PacketDecodeException("Unknown DataStore type"),
			};
		}
	}

	protected function encodePayload(ByteBufferWriter $out) : void{
		VarInt::writeUnsignedInt($out, count($this->values));
		foreach($this->values as $value){
			VarInt::writeUnsignedInt($out, $value->getTypeId());
			$value->write($out);
		}
	}

	public function handle(PacketHandlerInterface $handler) : bool{
		return $handler->handleClientboundDataStore($this);
	}
}
