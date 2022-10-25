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

use pocketmine\network\mcpe\protocol\serializer\PacketSerializer;
use pocketmine\network\mcpe\protocol\types\entity\MetadataProperty;
use pocketmine\network\mcpe\protocol\types\entity\PropertySyncData;

class SetActorDataPacket extends DataPacket implements ClientboundPacket, ServerboundPacket{ //TODO: check why this is serverbound
	public const NETWORK_ID = ProtocolInfo::SET_ACTOR_DATA_PACKET;

	public int $actorRuntimeId;
	/**
	 * @var MetadataProperty[]
	 * @phpstan-var array<int, MetadataProperty>
	 */
	public array $metadata;
	public PropertySyncData $syncedProperties;
	public int $tick = 0;

	/**
	 * @generate-create-func
	 * @param MetadataProperty[] $metadata
	 * @phpstan-param array<int, MetadataProperty> $metadata
	 */
	public static function create(int $actorRuntimeId, array $metadata, PropertySyncData $syncedProperties, int $tick) : self{
		$result = new self;
		$result->actorRuntimeId = $actorRuntimeId;
		$result->metadata = $metadata;
		$result->syncedProperties = $syncedProperties;
		$result->tick = $tick;
		return $result;
	}

	protected function decodePayload(PacketSerializer $in) : void{
		$this->actorRuntimeId = $in->getActorRuntimeId();
		$this->metadata = $in->getEntityMetadata();
		$this->syncedProperties = PropertySyncData::read($in);
		$this->tick = $in->getUnsignedVarLong();
	}

	protected function encodePayload(PacketSerializer $out) : void{
		$out->putActorRuntimeId($this->actorRuntimeId);
		$out->putEntityMetadata($this->metadata);
		$this->syncedProperties->write($out);
		$out->putUnsignedVarLong($this->tick);
	}

	public function handle(PacketHandlerInterface $handler) : bool{
		return $handler->handleSetActorData($this);
	}
}
