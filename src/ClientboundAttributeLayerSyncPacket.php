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
use pocketmine\network\mcpe\protocol\types\AttributeLayerSyncPayload;
use pocketmine\network\mcpe\protocol\types\AttributesRemoveEnvironment;
use pocketmine\network\mcpe\protocol\types\AttributesUpdateEnvironment;
use pocketmine\network\mcpe\protocol\types\AttributeUpdateLayers;
use pocketmine\network\mcpe\protocol\types\AttributeUpdateLayerSettings;

class ClientboundAttributeLayerSyncPacket extends DataPacket implements ClientboundPacket{
	public const NETWORK_ID = ProtocolInfo::CLIENTBOUND_ATTRIBUTE_LAYER_SYNC_PACKET;

	private AttributeLayerSyncPayload $payload;

	/**
	 * @generate-create-func
	 */
	public static function create(AttributeLayerSyncPayload $payload) : self{
		$result = new self;
		$result->payload = $payload;
		return $result;
	}

	protected function decodePayload(ByteBufferReader $in) : void{
		$this->payload = match(VarInt::readUnsignedInt($in)){
			AttributeUpdateLayers::ID => AttributeUpdateLayers::read($in),
			AttributeUpdateLayerSettings::ID => AttributeUpdateLayerSettings::read($in),
			AttributesUpdateEnvironment::ID => AttributesUpdateEnvironment::read($in),
			AttributesRemoveEnvironment::ID => AttributesRemoveEnvironment::read($in),
			default => throw new PacketDecodeException("Unknown ClientboundAttributeLayerSync type"),
		};
	}

	protected function encodePayload(ByteBufferWriter $out) : void{
		VarInt::writeUnsignedInt($out, $this->payload->getTypeId());
		$this->payload->write($out);
	}

	public function handle(PacketHandlerInterface $handler) : bool{
		return $handler->handleClientboundAttributeLayerSync($this);
	}
}
