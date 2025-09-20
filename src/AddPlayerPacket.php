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
use pocketmine\network\mcpe\protocol\types\DeviceOS;
use pocketmine\network\mcpe\protocol\types\entity\EntityLink;
use pocketmine\network\mcpe\protocol\types\entity\MetadataProperty;
use pocketmine\network\mcpe\protocol\types\entity\PropertySyncData;
use pocketmine\network\mcpe\protocol\types\inventory\ItemStackWrapper;
use Ramsey\Uuid\UuidInterface;
use function count;

class AddPlayerPacket extends DataPacket implements ClientboundPacket{
	public const NETWORK_ID = ProtocolInfo::ADD_PLAYER_PACKET;

	public UuidInterface $uuid;
	public string $username;
	public int $actorRuntimeId;
	public string $platformChatId = "";
	public Vector3 $position;
	public ?Vector3 $motion = null;
	public float $pitch = 0.0;
	public float $yaw = 0.0;
	public float $headYaw = 0.0;
	public ItemStackWrapper $item;
	public int $gameMode;
	/**
	 * @var MetadataProperty[]
	 * @phpstan-var array<int, MetadataProperty>
	 */
	public array $metadata = [];
	public PropertySyncData $syncedProperties;

	public UpdateAbilitiesPacket $abilitiesPacket;

	/** @var EntityLink[] */
	public array $links = [];
	public string $deviceId = ""; //TODO: fill player's device ID (???)
	public int $buildPlatform = DeviceOS::UNKNOWN;

	/**
	 * @generate-create-func
	 * @param MetadataProperty[] $metadata
	 * @param EntityLink[]       $links
	 * @phpstan-param array<int, MetadataProperty> $metadata
	 */
	public static function create(
		UuidInterface $uuid,
		string $username,
		int $actorRuntimeId,
		string $platformChatId,
		Vector3 $position,
		?Vector3 $motion,
		float $pitch,
		float $yaw,
		float $headYaw,
		ItemStackWrapper $item,
		int $gameMode,
		array $metadata,
		PropertySyncData $syncedProperties,
		UpdateAbilitiesPacket $abilitiesPacket,
		array $links,
		string $deviceId,
		int $buildPlatform,
	) : self{
		$result = new self;
		$result->uuid = $uuid;
		$result->username = $username;
		$result->actorRuntimeId = $actorRuntimeId;
		$result->platformChatId = $platformChatId;
		$result->position = $position;
		$result->motion = $motion;
		$result->pitch = $pitch;
		$result->yaw = $yaw;
		$result->headYaw = $headYaw;
		$result->item = $item;
		$result->gameMode = $gameMode;
		$result->metadata = $metadata;
		$result->syncedProperties = $syncedProperties;
		$result->abilitiesPacket = $abilitiesPacket;
		$result->links = $links;
		$result->deviceId = $deviceId;
		$result->buildPlatform = $buildPlatform;
		return $result;
	}

	protected function decodePayload(ByteBufferReader $in) : void{
		$this->uuid = CommonTypes::getUUID($in);
		$this->username = CommonTypes::getString($in);
		$this->actorRuntimeId = CommonTypes::getActorRuntimeId($in);
		$this->platformChatId = CommonTypes::getString($in);
		$this->position = CommonTypes::getVector3($in);
		$this->motion = CommonTypes::getVector3($in);
		$this->pitch = LE::readFloat($in);
		$this->yaw = LE::readFloat($in);
		$this->headYaw = LE::readFloat($in);
		$this->item = CommonTypes::getItemStackWrapper($in);
		$this->gameMode = VarInt::readSignedInt($in);
		$this->metadata = CommonTypes::getEntityMetadata($in);
		$this->syncedProperties = PropertySyncData::read($in);

		$this->abilitiesPacket = new UpdateAbilitiesPacket();
		$this->abilitiesPacket->decodePayload($in);

		$linkCount = VarInt::readUnsignedInt($in);
		for($i = 0; $i < $linkCount; ++$i){
			$this->links[$i] = CommonTypes::getEntityLink($in);
		}

		$this->deviceId = CommonTypes::getString($in);
		$this->buildPlatform = LE::readSignedInt($in);
	}

	protected function encodePayload(ByteBufferWriter $out) : void{
		CommonTypes::putUUID($out, $this->uuid);
		CommonTypes::putString($out, $this->username);
		CommonTypes::putActorRuntimeId($out, $this->actorRuntimeId);
		CommonTypes::putString($out, $this->platformChatId);
		CommonTypes::putVector3($out, $this->position);
		CommonTypes::putVector3Nullable($out, $this->motion);
		LE::writeFloat($out, $this->pitch);
		LE::writeFloat($out, $this->yaw);
		LE::writeFloat($out, $this->headYaw);
		CommonTypes::putItemStackWrapper($out, $this->item);
		VarInt::writeSignedInt($out, $this->gameMode);
		CommonTypes::putEntityMetadata($out, $this->metadata);
		$this->syncedProperties->write($out);

		$this->abilitiesPacket->encodePayload($out);

		VarInt::writeUnsignedInt($out, count($this->links));
		foreach($this->links as $link){
			CommonTypes::putEntityLink($out, $link);
		}

		CommonTypes::putString($out, $this->deviceId);
		LE::writeSignedInt($out, $this->buildPlatform);
	}

	public function handle(PacketHandlerInterface $handler) : bool{
		return $handler->handleAddPlayer($this);
	}
}
