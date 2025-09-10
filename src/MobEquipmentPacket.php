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
use pocketmine\network\mcpe\protocol\serializer\CommonTypes;
use pocketmine\network\mcpe\protocol\types\inventory\ItemStackWrapper;

class MobEquipmentPacket extends DataPacket implements ClientboundPacket, ServerboundPacket{
	public const NETWORK_ID = ProtocolInfo::MOB_EQUIPMENT_PACKET;

	public int $actorRuntimeId;
	public ItemStackWrapper $item;
	public int $inventorySlot;
	public int $hotbarSlot;
	public int $windowId = 0;

	/**
	 * @generate-create-func
	 */
	public static function create(int $actorRuntimeId, ItemStackWrapper $item, int $inventorySlot, int $hotbarSlot, int $windowId) : self{
		$result = new self;
		$result->actorRuntimeId = $actorRuntimeId;
		$result->item = $item;
		$result->inventorySlot = $inventorySlot;
		$result->hotbarSlot = $hotbarSlot;
		$result->windowId = $windowId;
		return $result;
	}

	protected function decodePayload(ByteBufferReader $in) : void{
		$this->actorRuntimeId = CommonTypes::getActorRuntimeId($in);
		$this->item = CommonTypes::getItemStackWrapper($in);
		$this->inventorySlot = Byte::readUnsigned($in);
		$this->hotbarSlot = Byte::readUnsigned($in);
		$this->windowId = Byte::readUnsigned($in);
	}

	protected function encodePayload(ByteBufferWriter $out) : void{
		CommonTypes::putActorRuntimeId($out, $this->actorRuntimeId);
		CommonTypes::putItemStackWrapper($out, $this->item);
		Byte::writeUnsigned($out, $this->inventorySlot);
		Byte::writeUnsigned($out, $this->hotbarSlot);
		Byte::writeUnsigned($out, $this->windowId);
	}

	public function handle(PacketHandlerInterface $handler) : bool{
		return $handler->handleMobEquipment($this);
	}
}
