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
use pocketmine\network\mcpe\protocol\types\SyncWorldClocksAddTimeMarker;
use pocketmine\network\mcpe\protocol\types\SyncWorldClocksInitializeRegistry;
use pocketmine\network\mcpe\protocol\types\SyncWorldClocksPayload;
use pocketmine\network\mcpe\protocol\types\SyncWorldClocksRemoveTimeMarker;
use pocketmine\network\mcpe\protocol\types\SyncWorldClocksSyncState;

class SyncWorldClocksPacket extends DataPacket implements ClientboundPacket{
	public const NETWORK_ID = ProtocolInfo::SYNC_WORLD_CLOCKS_PACKET;

	private SyncWorldClocksPayload $payload;

	/**
	 * @generate-create-func
	 */
	public static function create(SyncWorldClocksPayload $payload) : self{
		$result = new self;
		$result->payload = $payload;
		return $result;
	}

	protected function decodePayload(ByteBufferReader $in) : void{
		$this->payload = match(VarInt::readUnsignedInt($in)){
			SyncWorldClocksSyncState::ID => SyncWorldClocksSyncState::read($in),
			SyncWorldClocksInitializeRegistry::ID => SyncWorldClocksInitializeRegistry::read($in),
			SyncWorldClocksAddTimeMarker::ID => SyncWorldClocksAddTimeMarker::read($in),
			SyncWorldClocksRemoveTimeMarker::ID => SyncWorldClocksRemoveTimeMarker::read($in),
			default => throw new PacketDecodeException("Unknown SyncWorldClocks type"),
		};
	}

	protected function encodePayload(ByteBufferWriter $out) : void{
		VarInt::writeUnsignedInt($out, $this->payload->getTypeId());
		$this->payload->write($out);
	}

	public function handle(PacketHandlerInterface $handler) : bool{
		return $handler->handleSyncWorldClocks($this);
	}
}
