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
use pocketmine\math\Vector3;
use pocketmine\network\mcpe\protocol\serializer\CommonTypes;
use pocketmine\network\mcpe\protocol\types\entity\MetadataProperty;
use pocketmine\network\mcpe\protocol\types\inventory\ItemStackWrapper;

class AddItemActorPacket extends DataPacket implements ClientboundPacket{
	public const NETWORK_ID = ProtocolInfo::ADD_ITEM_ACTOR_PACKET;

	public int $actorUniqueId;
	public int $actorRuntimeId;
	public ItemStackWrapper $item;
	public Vector3 $position;
	public ?Vector3 $motion = null;
	/**
	 * @var MetadataProperty[]
	 * @phpstan-var array<int, MetadataProperty>
	 */
	public array $metadata = [];
	public bool $isFromFishing = false;

	/**
	 * @generate-create-func
	 * @param MetadataProperty[] $metadata
	 * @phpstan-param array<int, MetadataProperty> $metadata
	 */
	public static function create(
		int $actorUniqueId,
		int $actorRuntimeId,
		ItemStackWrapper $item,
		Vector3 $position,
		?Vector3 $motion,
		array $metadata,
		bool $isFromFishing,
	) : self{
		$result = new self;
		$result->actorUniqueId = $actorUniqueId;
		$result->actorRuntimeId = $actorRuntimeId;
		$result->item = $item;
		$result->position = $position;
		$result->motion = $motion;
		$result->metadata = $metadata;
		$result->isFromFishing = $isFromFishing;
		return $result;
	}

	protected function decodePayload(ByteBufferReader $in) : void{
		$this->actorUniqueId = CommonTypes::getActorUniqueId($in);
		$this->actorRuntimeId = CommonTypes::getActorRuntimeId($in);
		$this->item = CommonTypes::getItemStackWrapper($in);
		$this->position = CommonTypes::getVector3($in);
		$this->motion = CommonTypes::getVector3($in);
		$this->metadata = CommonTypes::getEntityMetadata($in);
		$this->isFromFishing = CommonTypes::getBool($in);
	}

	protected function encodePayload(ByteBufferWriter $out) : void{
		CommonTypes::putActorUniqueId($out, $this->actorUniqueId);
		CommonTypes::putActorRuntimeId($out, $this->actorRuntimeId);
		CommonTypes::putItemStackWrapper($out, $this->item);
		CommonTypes::putVector3($out, $this->position);
		CommonTypes::putVector3Nullable($out, $this->motion);
		CommonTypes::putEntityMetadata($out, $this->metadata);
		CommonTypes::putBool($out, $this->isFromFishing);
	}

	public function handle(PacketHandlerInterface $handler) : bool{
		return $handler->handleAddItemActor($this);
	}
}
