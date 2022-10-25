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

use pocketmine\math\Vector3;
use pocketmine\network\mcpe\protocol\serializer\PacketSerializer;
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

	protected function decodePayload(PacketSerializer $in) : void{
		$this->actorUniqueId = $in->getActorUniqueId();
		$this->actorRuntimeId = $in->getActorRuntimeId();
		$this->type = $in->getString();
		$this->position = $in->getVector3();
		$this->motion = $in->getVector3();
		$this->pitch = $in->getLFloat();
		$this->yaw = $in->getLFloat();
		$this->headYaw = $in->getLFloat();
		$this->bodyYaw = $in->getLFloat();

		$attrCount = $in->getUnsignedVarInt();
		for($i = 0; $i < $attrCount; ++$i){
			$id = $in->getString();
			$min = $in->getLFloat();
			$current = $in->getLFloat();
			$max = $in->getLFloat();
			$this->attributes[] = new Attribute($id, $min, $max, $current, $current, []);
		}

		$this->metadata = $in->getEntityMetadata();
		$this->syncedProperties = PropertySyncData::read($in);

		$linkCount = $in->getUnsignedVarInt();
		for($i = 0; $i < $linkCount; ++$i){
			$this->links[] = $in->getEntityLink();
		}
	}

	protected function encodePayload(PacketSerializer $out) : void{
		$out->putActorUniqueId($this->actorUniqueId);
		$out->putActorRuntimeId($this->actorRuntimeId);
		$out->putString($this->type);
		$out->putVector3($this->position);
		$out->putVector3Nullable($this->motion);
		$out->putLFloat($this->pitch);
		$out->putLFloat($this->yaw);
		$out->putLFloat($this->headYaw);
		$out->putLFloat($this->bodyYaw);

		$out->putUnsignedVarInt(count($this->attributes));
		foreach($this->attributes as $attribute){
			$out->putString($attribute->getId());
			$out->putLFloat($attribute->getMin());
			$out->putLFloat($attribute->getCurrent());
			$out->putLFloat($attribute->getMax());
		}

		$out->putEntityMetadata($this->metadata);
		$this->syncedProperties->write($out);

		$out->putUnsignedVarInt(count($this->links));
		foreach($this->links as $link){
			$out->putEntityLink($link);
		}
	}

	public function handle(PacketHandlerInterface $handler) : bool{
		return $handler->handleAddActor($this);
	}
}
