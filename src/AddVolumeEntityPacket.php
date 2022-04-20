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
use pocketmine\network\mcpe\protocol\types\BlockPosition;
use pocketmine\network\mcpe\protocol\types\CacheableNbt;

class AddVolumeEntityPacket extends DataPacket implements ClientboundPacket{
	public const NETWORK_ID = ProtocolInfo::ADD_VOLUME_ENTITY_PACKET;

	private int $entityNetId;
	/** @phpstan-var CacheableNbt<\pocketmine\nbt\tag\CompoundTag> */
	private CacheableNbt $data;
	private string $jsonIdentifier;
	private string $instanceName;
	private BlockPosition $minBound;
	private BlockPosition $maxBound;
	private int $dimension;
	private string $engineVersion;

	/**
	 * @generate-create-func
	 * @phpstan-param CacheableNbt<\pocketmine\nbt\tag\CompoundTag> $data
	 */
	public static function create(
		int $entityNetId,
		CacheableNbt $data,
		string $jsonIdentifier,
		string $instanceName,
		BlockPosition $minBound,
		BlockPosition $maxBound,
		int $dimension,
		string $engineVersion,
	) : self{
		$result = new self;
		$result->entityNetId = $entityNetId;
		$result->data = $data;
		$result->jsonIdentifier = $jsonIdentifier;
		$result->instanceName = $instanceName;
		$result->minBound = $minBound;
		$result->maxBound = $maxBound;
		$result->dimension = $dimension;
		$result->engineVersion = $engineVersion;
		return $result;
	}

	public function getEntityNetId() : int{ return $this->entityNetId; }

	/** @phpstan-return CacheableNbt<\pocketmine\nbt\tag\CompoundTag> */
	public function getData() : CacheableNbt{ return $this->data; }

	public function getJsonIdentifier() : string{ return $this->jsonIdentifier; }

	public function getInstanceName() : string{ return $this->instanceName; }

	public function getMinBound() : BlockPosition{ return $this->minBound; }

	public function getMaxBound() : BlockPosition{ return $this->maxBound; }

	public function getDimension() : int{ return $this->dimension; }

	public function getEngineVersion() : string{ return $this->engineVersion; }

	protected function decodePayload(PacketSerializer $in) : void{
		$this->entityNetId = $in->getUnsignedVarInt();
		$this->data = new CacheableNbt($in->getNbtCompoundRoot());
		$this->jsonIdentifier = $in->getString();
		$this->instanceName = $in->getString();
		$this->minBound = $in->getBlockPosition();
		$this->maxBound = $in->getBlockPosition();
		$this->dimension = $in->getVarInt();
		$this->engineVersion = $in->getString();
	}

	protected function encodePayload(PacketSerializer $out) : void{
		$out->putUnsignedVarInt($this->entityNetId);
		$out->put($this->data->getEncodedNbt());
		$out->putString($this->jsonIdentifier);
		$out->putString($this->instanceName);
		$out->putBlockPosition($this->minBound);
		$out->putBlockPosition($this->maxBound);
		$out->putVarInt($this->dimension);
		$out->putString($this->engineVersion);
	}

	public function handle(PacketHandlerInterface $handler) : bool{
		return $handler->handleAddVolumeEntity($this);
	}
}
