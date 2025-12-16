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
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\network\mcpe\protocol\serializer\CommonTypes;
use pocketmine\network\mcpe\protocol\types\BlockPaletteEntry;
use pocketmine\network\mcpe\protocol\types\CacheableNbt;
use pocketmine\network\mcpe\protocol\types\LevelSettings;
use pocketmine\network\mcpe\protocol\types\NetworkPermissions;
use pocketmine\network\mcpe\protocol\types\PlayerMovementSettings;
use Ramsey\Uuid\UuidInterface;
use function count;

class StartGamePacket extends DataPacket implements ClientboundPacket{
	public const NETWORK_ID = ProtocolInfo::START_GAME_PACKET;

	public int $actorUniqueId;
	public int $actorRuntimeId;
	public int $playerGamemode;

	public Vector3 $playerPosition;

	public float $pitch;
	public float $yaw;

	/** @phpstan-var CacheableNbt<CompoundTag>  */
	public CacheableNbt $playerActorProperties; //same as SyncActorPropertyPacket content

	public LevelSettings $levelSettings;

	public string $levelId = ""; //base64 string, usually the same as world folder name in vanilla
	public string $worldName;
	public string $premiumWorldTemplateId = "";
	public bool $isTrial = false;
	public PlayerMovementSettings $playerMovementSettings;
	public int $currentTick = 0; //only used if isTrial is true
	public int $enchantmentSeed = 0;
	public string $multiplayerCorrelationId = ""; //TODO: this should be filled with a UUID of some sort
	public bool $enableNewInventorySystem = false; //TODO
	public string $serverSoftwareVersion;
	public UuidInterface $worldTemplateId; //why is this here twice ??? mojang
	public bool $enableClientSideChunkGeneration;
	public bool $blockNetworkIdsAreHashes = false; //new in 1.19.80, possibly useful for multi version
	public NetworkPermissions $networkPermissions;

	/**
	 * @var BlockPaletteEntry[]
	 * @phpstan-var list<BlockPaletteEntry>
	 */
	public array $blockPalette = [];

	/**
	 * Checksum of the full block palette. This is a hash of some weird stringified version of the NBT.
	 * This is used along with the baseGameVersion to check for inconsistencies in the block palette.
	 * Fill with 0 if you don't want to bother having the client verify the palette (seems pointless anyway).
	 */
	public int $blockPaletteChecksum;

	/**
	 * @generate-create-func
	 * @param BlockPaletteEntry[] $blockPalette
	 * @phpstan-param CacheableNbt<CompoundTag> $playerActorProperties
	 * @phpstan-param list<BlockPaletteEntry>   $blockPalette
	 */
	public static function create(
		int $actorUniqueId,
		int $actorRuntimeId,
		int $playerGamemode,
		Vector3 $playerPosition,
		float $pitch,
		float $yaw,
		CacheableNbt $playerActorProperties,
		LevelSettings $levelSettings,
		string $levelId,
		string $worldName,
		string $premiumWorldTemplateId,
		bool $isTrial,
		PlayerMovementSettings $playerMovementSettings,
		int $currentTick,
		int $enchantmentSeed,
		string $multiplayerCorrelationId,
		bool $enableNewInventorySystem,
		string $serverSoftwareVersion,
		UuidInterface $worldTemplateId,
		bool $enableClientSideChunkGeneration,
		bool $blockNetworkIdsAreHashes,
		NetworkPermissions $networkPermissions,
		array $blockPalette,
		int $blockPaletteChecksum,
	) : self{
		$result = new self;
		$result->actorUniqueId = $actorUniqueId;
		$result->actorRuntimeId = $actorRuntimeId;
		$result->playerGamemode = $playerGamemode;
		$result->playerPosition = $playerPosition;
		$result->pitch = $pitch;
		$result->yaw = $yaw;
		$result->playerActorProperties = $playerActorProperties;
		$result->levelSettings = $levelSettings;
		$result->levelId = $levelId;
		$result->worldName = $worldName;
		$result->premiumWorldTemplateId = $premiumWorldTemplateId;
		$result->isTrial = $isTrial;
		$result->playerMovementSettings = $playerMovementSettings;
		$result->currentTick = $currentTick;
		$result->enchantmentSeed = $enchantmentSeed;
		$result->multiplayerCorrelationId = $multiplayerCorrelationId;
		$result->enableNewInventorySystem = $enableNewInventorySystem;
		$result->serverSoftwareVersion = $serverSoftwareVersion;
		$result->worldTemplateId = $worldTemplateId;
		$result->enableClientSideChunkGeneration = $enableClientSideChunkGeneration;
		$result->blockNetworkIdsAreHashes = $blockNetworkIdsAreHashes;
		$result->networkPermissions = $networkPermissions;
		$result->blockPalette = $blockPalette;
		$result->blockPaletteChecksum = $blockPaletteChecksum;
		return $result;
	}

	protected function decodePayload(ByteBufferReader $in) : void{
		$this->actorUniqueId = CommonTypes::getActorUniqueId($in);
		$this->actorRuntimeId = CommonTypes::getActorRuntimeId($in);
		$this->playerGamemode = VarInt::readSignedInt($in);

		$this->playerPosition = CommonTypes::getVector3($in);

		$this->pitch = LE::readFloat($in);
		$this->yaw = LE::readFloat($in);

		$this->levelSettings = LevelSettings::read($in);

		$this->levelId = CommonTypes::getString($in);
		$this->worldName = CommonTypes::getString($in);
		$this->premiumWorldTemplateId = CommonTypes::getString($in);
		$this->isTrial = CommonTypes::getBool($in);
		$this->playerMovementSettings = PlayerMovementSettings::read($in);
		$this->currentTick = LE::readUnsignedLong($in);

		$this->enchantmentSeed = VarInt::readSignedInt($in);

		$this->blockPalette = [];
		for($i = 0, $len = VarInt::readUnsignedInt($in); $i < $len; ++$i){
			$blockName = CommonTypes::getString($in);
			$state = CommonTypes::getNbtCompoundRoot($in);
			$this->blockPalette[] = new BlockPaletteEntry($blockName, new CacheableNbt($state));
		}

		$this->multiplayerCorrelationId = CommonTypes::getString($in);
		$this->enableNewInventorySystem = CommonTypes::getBool($in);
		$this->serverSoftwareVersion = CommonTypes::getString($in);
		$this->playerActorProperties = new CacheableNbt(CommonTypes::getNbtCompoundRoot($in));
		$this->blockPaletteChecksum = LE::readUnsignedLong($in);
		$this->worldTemplateId = CommonTypes::getUUID($in);
		$this->enableClientSideChunkGeneration = CommonTypes::getBool($in);
		$this->blockNetworkIdsAreHashes = CommonTypes::getBool($in);
		$this->networkPermissions = NetworkPermissions::decode($in);
	}

	protected function encodePayload(ByteBufferWriter $out) : void{
		CommonTypes::putActorUniqueId($out, $this->actorUniqueId);
		CommonTypes::putActorRuntimeId($out, $this->actorRuntimeId);
		VarInt::writeSignedInt($out, $this->playerGamemode);

		CommonTypes::putVector3($out, $this->playerPosition);

		LE::writeFloat($out, $this->pitch);
		LE::writeFloat($out, $this->yaw);

		$this->levelSettings->write($out);

		CommonTypes::putString($out, $this->levelId);
		CommonTypes::putString($out, $this->worldName);
		CommonTypes::putString($out, $this->premiumWorldTemplateId);
		CommonTypes::putBool($out, $this->isTrial);
		$this->playerMovementSettings->write($out);
		LE::writeUnsignedLong($out, $this->currentTick);

		VarInt::writeSignedInt($out, $this->enchantmentSeed);

		VarInt::writeUnsignedInt($out, count($this->blockPalette));
		foreach($this->blockPalette as $entry){
			CommonTypes::putString($out, $entry->getName());
			$out->writeByteArray($entry->getStates()->getEncodedNbt());
		}

		CommonTypes::putString($out, $this->multiplayerCorrelationId);
		CommonTypes::putBool($out, $this->enableNewInventorySystem);
		CommonTypes::putString($out, $this->serverSoftwareVersion);
		$out->writeByteArray($this->playerActorProperties->getEncodedNbt());
		LE::writeUnsignedLong($out, $this->blockPaletteChecksum);
		CommonTypes::putUUID($out, $this->worldTemplateId);
		CommonTypes::putBool($out, $this->enableClientSideChunkGeneration);
		CommonTypes::putBool($out, $this->blockNetworkIdsAreHashes);
		$this->networkPermissions->encode($out);
	}

	public function handle(PacketHandlerInterface $handler) : bool{
		return $handler->handleStartGame($this);
	}
}
