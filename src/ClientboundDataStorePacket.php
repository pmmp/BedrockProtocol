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
use pocketmine\network\mcpe\protocol\types\ddui\DataStoreChange;
use pocketmine\network\mcpe\protocol\types\ddui\DataStoreOperation;
use pocketmine\network\mcpe\protocol\types\ddui\DataStoreOperationType;
use pocketmine\network\mcpe\protocol\types\ddui\DataStoreRemoval;
use pocketmine\network\mcpe\protocol\types\ddui\DataStoreUpdate;
use function count;

class ClientboundDataStorePacket extends DataPacket implements ClientboundPacket{
	public const NETWORK_ID = ProtocolInfo::CLIENTBOUND_DATA_STORE_PACKET;

	/**
	 * @var DataStoreOperation[]
	 * @phpstan-var list<DataStoreOperation>
	 */
	public array $values = [];

	/**
	 * @generate-create-func
	 * @param DataStoreOperation[] $values
	 * @phpstan-param list<DataStoreOperation> $values
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
				DataStoreOperationType::UPDATE => DataStoreUpdate::read($in),
				DataStoreOperationType::CHANGE => DataStoreChange::read($in),
				DataStoreOperationType::REMOVAL => DataStoreRemoval::read($in),
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
