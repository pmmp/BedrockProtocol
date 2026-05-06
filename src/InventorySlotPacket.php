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
use pmmp\encoding\VarInt;
use pocketmine\network\mcpe\protocol\serializer\CommonTypes;
use pocketmine\network\mcpe\protocol\types\inventory\FullContainerName;
use pocketmine\network\mcpe\protocol\types\inventory\ItemStackWrapper;

class InventorySlotPacket extends DataPacket implements ClientboundPacket{
	public const NETWORK_ID = ProtocolInfo::INVENTORY_SLOT_PACKET;

	public int $windowId;
	public int $inventorySlot;
	public ?FullContainerName $containerName;
	public ?ItemStackWrapper $storage;
	public ItemStackWrapper $item;

	/**
	 * @generate-create-func
	 */
	public static function create(int $windowId, int $inventorySlot, ?FullContainerName $containerName, ?ItemStackWrapper $storage, ItemStackWrapper $item) : self{
		$result = new self;
		$result->windowId = $windowId;
		$result->inventorySlot = $inventorySlot;
		$result->containerName = $containerName;
		$result->storage = $storage;
		$result->item = $item;
		return $result;
	}

	protected function decodePayload(ByteBufferReader $in) : void{
		$this->windowId = VarInt::readUnsignedInt($in);
		$this->inventorySlot = VarInt::readUnsignedInt($in);
		$this->containerName = CommonTypes::readOptional($in, FullContainerName::read(...));
		$this->storage = CommonTypes::readOptional($in, CommonTypes::getNetworkItemStackDescriptor(...));
		$this->item = CommonTypes::getNetworkItemStackDescriptor($in);
	}

	protected function encodePayload(ByteBufferWriter $out) : void{
		VarInt::writeUnsignedInt($out, $this->windowId);
		VarInt::writeUnsignedInt($out, $this->inventorySlot);
		CommonTypes::writeOptional($out, $this->containerName, fn(ByteBufferWriter $out, FullContainerName $v) => $v->write($out));
		CommonTypes::writeOptional($out, $this->storage, CommonTypes::putNetworkItemStackDescriptor(...));
		CommonTypes::putNetworkItemStackDescriptor($out, $this->item);
	}

	public function handle(PacketHandlerInterface $handler) : bool{
		return $handler->handleInventorySlot($this);
	}
}
