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
use pocketmine\network\mcpe\protocol\types\CacheableNbt;

class UpdateEquipPacket extends DataPacket implements ClientboundPacket{
	public const NETWORK_ID = ProtocolInfo::UPDATE_EQUIP_PACKET;

	public int $windowId;
	public int $windowType;
	public int $windowSlotCount; //useless, seems to be part of a standard container header
	public int $actorUniqueId;
	/** @phpstan-var CacheableNbt<\pocketmine\nbt\tag\CompoundTag> */
	public CacheableNbt $nbt;

	/**
	 * @generate-create-func
	 * @phpstan-param CacheableNbt<\pocketmine\nbt\tag\CompoundTag> $nbt
	 */
	public static function create(int $windowId, int $windowType, int $windowSlotCount, int $actorUniqueId, CacheableNbt $nbt) : self{
		$result = new self;
		$result->windowId = $windowId;
		$result->windowType = $windowType;
		$result->windowSlotCount = $windowSlotCount;
		$result->actorUniqueId = $actorUniqueId;
		$result->nbt = $nbt;
		return $result;
	}

	protected function decodePayload(PacketSerializer $in) : void{
		$this->windowId = $in->getByte();
		$this->windowType = $in->getByte();
		$this->windowSlotCount = $in->getVarInt();
		$this->actorUniqueId = $in->getActorUniqueId();
		$this->nbt = new CacheableNbt($in->getNbtCompoundRoot());
	}

	protected function encodePayload(PacketSerializer $out) : void{
		$out->putByte($this->windowId);
		$out->putByte($this->windowType);
		$out->putVarInt($this->windowSlotCount);
		$out->putActorUniqueId($this->actorUniqueId);
		$out->put($this->nbt->getEncodedNbt());
	}

	public function handle(PacketHandlerInterface $handler) : bool{
		return $handler->handleUpdateEquip($this);
	}
}
