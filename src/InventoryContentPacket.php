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
use function count;

class InventoryContentPacket extends DataPacket implements ClientboundPacket{
	public const NETWORK_ID = ProtocolInfo::INVENTORY_CONTENT_PACKET;

	public int $windowId;
	/** @var ItemStackWrapper[] */
	public array $items = [];
	public FullContainerName $containerName;
	public ItemStackWrapper $storage;

	/**
	 * @generate-create-func
	 * @param ItemStackWrapper[] $items
	 */
	public static function create(int $windowId, array $items, FullContainerName $containerName, ItemStackWrapper $storage) : self{
		$result = new self;
		$result->windowId = $windowId;
		$result->items = $items;
		$result->containerName = $containerName;
		$result->storage = $storage;
		return $result;
	}

	protected function decodePayload(ByteBufferReader $in) : void{
		$this->windowId = VarInt::readUnsignedInt($in);
		$count = VarInt::readUnsignedInt($in);
		for($i = 0; $i < $count; ++$i){
			$this->items[] = CommonTypes::getItemStackWrapper($in);
		}
		$this->containerName = FullContainerName::read($in);
		$this->storage = CommonTypes::getItemStackWrapper($in);
	}

	protected function encodePayload(ByteBufferWriter $out) : void{
		VarInt::writeUnsignedInt($out, $this->windowId);
		VarInt::writeUnsignedInt($out, count($this->items));
		foreach($this->items as $item){
			CommonTypes::putItemStackWrapper($out, $item);
		}
		$this->containerName->write($out);
		CommonTypes::putItemStackWrapper($out, $this->storage);
	}

	public function handle(PacketHandlerInterface $handler) : bool{
		return $handler->handleInventoryContent($this);
	}
}
