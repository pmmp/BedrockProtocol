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
use pmmp\encoding\LE;
use pmmp\encoding\VarInt;
use pocketmine\math\Vector3;
use pocketmine\network\mcpe\protocol\serializer\CommonTypes;
use pocketmine\network\mcpe\protocol\types\entity\Attribute;
use pocketmine\network\mcpe\protocol\types\entity\EntityLink;
use pocketmine\network\mcpe\protocol\types\entity\MetadataProperty;
use pocketmine\network\mcpe\protocol\types\entity\PropertySyncData;
use function count;

class AddActorPacket extends DataPacket implements ClientboundPacket{
	public const NETWORK_ID = ProtocolInfo::ADD_ACTOR_PACKET;

	public int $actorUniqueId;
	public int $actorRuntimeId;
	public string $type;
	public Vector3 $position;
	public ?Vector3 $motion = null;
	public float $pitch = 0.0;
	public float $yaw = 0.0;
	public float $headYaw = 0.0;
	public float $bodyYaw = 0.0; //???

	/** @var Attribute[] */
	public array $attributes = [];
	/**
	 * @var MetadataProperty[]
	 * @phpstan-var array<int, MetadataProperty>
	 */
	public array $metadata = [];
	public PropertySyncData $syncedProperties;
	/** @var EntityLink[] */
	public array $links = [];

	/**
	 * @generate-create-func
	 * @param Attribute[]        $attributes
	 * @param MetadataProperty[] $metadata
	 * @param EntityLink[]       $links
	 * @phpstan-param array<int, MetadataProperty> $metadata
	 */
	public static function create(
		int $actorUniqueId,
		int $actorRuntimeId,
		string $type,
		Vector3 $position,
		?Vector3 $motion,
		float $pitch,
		float $yaw,
		float $headYaw,
		float $bodyYaw,
		array $attributes,
		array $metadata,
		PropertySyncData $syncedProperties,
		array $links,
	) : self{
		$result = new self;
		$result->actorUniqueId = $actorUniqueId;
		$result->actorRuntimeId = $actorRuntimeId;
		$result->type = $type;
		$result->position = $position;
		$result->motion = $motion;
		$result->pitch = $pitch;
		$result->yaw = $yaw;
		$result->headYaw = $headYaw;
		$result->bodyYaw = $bodyYaw;
		$result->attributes = $attributes;
		$result->metadata = $metadata;
		$result->syncedProperties = $syncedProperties;
		$result->links = $links;
		return $result;
	}

	protected function decodePayload(ByteBufferReader $in) : void{
		$this->actorUniqueId = CommonTypes::getActorUniqueId($in);
		$this->actorRuntimeId = CommonTypes::getActorRuntimeId($in);
		$this->type = CommonTypes::getString($in);
		$this->position = CommonTypes::getVector3($in);
		$this->motion = CommonTypes::getVector3($in);
		$this->pitch = LE::readFloat($in);
		$this->yaw = LE::readFloat($in);
		$this->headYaw = LE::readFloat($in);
		$this->bodyYaw = LE::readFloat($in);

		$attrCount = VarInt::readUnsignedInt($in);
		for($i = 0; $i < $attrCount; ++$i){
			$id = CommonTypes::getString($in);
			$min = LE::readFloat($in);
			$current = LE::readFloat($in);
			$max = LE::readFloat($in);
			$this->attributes[] = new Attribute($id, $min, $max, $current, $current, []);
		}

		$this->metadata = CommonTypes::getEntityMetadata($in);
		$this->syncedProperties = PropertySyncData::read($in);

		$linkCount = VarInt::readUnsignedInt($in);
		for($i = 0; $i < $linkCount; ++$i){
			$this->links[] = CommonTypes::getEntityLink($in);
		}
	}

	protected function encodePayload(ByteBufferWriter $out) : void{
		CommonTypes::putActorUniqueId($out, $this->actorUniqueId);
		CommonTypes::putActorRuntimeId($out, $this->actorRuntimeId);
		CommonTypes::putString($out, $this->type);
		CommonTypes::putVector3($out, $this->position);
		CommonTypes::putVector3Nullable($out, $this->motion);
		LE::writeFloat($out, $this->pitch);
		LE::writeFloat($out, $this->yaw);
		LE::writeFloat($out, $this->headYaw);
		LE::writeFloat($out, $this->bodyYaw);

		VarInt::writeUnsignedInt($out, count($this->attributes));
		foreach($this->attributes as $attribute){
			CommonTypes::putString($out, $attribute->getId());
			LE::writeFloat($out, $attribute->getMin());
			LE::writeFloat($out, $attribute->getCurrent());
			LE::writeFloat($out, $attribute->getMax());
		}

		CommonTypes::putEntityMetadata($out, $this->metadata);
		$this->syncedProperties->write($out);

		VarInt::writeUnsignedInt($out, count($this->links));
		foreach($this->links as $link){
			CommonTypes::putEntityLink($out, $link);
		}
	}

	public function handle(PacketHandlerInterface $handler) : bool{
		return $handler->handleAddActor($this);
	}
}
