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

use pmmp\encoding\Byte;
use pmmp\encoding\ByteBufferReader;
use pmmp\encoding\ByteBufferWriter;
use pmmp\encoding\VarInt;
use pocketmine\network\mcpe\protocol\serializer\CommonTypes;
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

	protected function decodePayload(ByteBufferReader $in) : void{
		$this->windowId = Byte::readUnsigned($in);
		$this->windowType = Byte::readUnsigned($in);
		$this->windowSlotCount = VarInt::readSignedInt($in);
		$this->actorUniqueId = CommonTypes::getActorUniqueId($in);
		$this->nbt = new CacheableNbt(CommonTypes::getNbtCompoundRoot($in));
	}

	protected function encodePayload(ByteBufferWriter $out) : void{
		Byte::writeUnsigned($out, $this->windowId);
		Byte::writeUnsigned($out, $this->windowType);
		VarInt::writeSignedInt($out, $this->windowSlotCount);
		CommonTypes::putActorUniqueId($out, $this->actorUniqueId);
		$out->writeByteArray($this->nbt->getEncodedNbt());
	}

	public function handle(PacketHandlerInterface $handler) : bool{
		return $handler->handleUpdateEquip($this);
	}
}
