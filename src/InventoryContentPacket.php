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
use pocketmine\network\mcpe\protocol\types\inventory\FullContainerName;
use pocketmine\network\mcpe\protocol\types\inventory\ItemStackWrapper;
use function count;

class InventoryContentPacket extends DataPacket implements ClientboundPacket{
	public const NETWORK_ID = ProtocolInfo::INVENTORY_CONTENT_PACKET;

	public int $windowId;
	/** @var ItemStackWrapper[] */
	public array $items = [];
	public FullContainerName $containerName;
	public int $dynamicContainerSize;

	/**
	 * @generate-create-func
	 * @param ItemStackWrapper[] $items
	 */
	public static function create(int $windowId, array $items, FullContainerName $containerName, int $dynamicContainerSize) : self{
		$result = new self;
		$result->windowId = $windowId;
		$result->items = $items;
		$result->containerName = $containerName;
		$result->dynamicContainerSize = $dynamicContainerSize;
		return $result;
	}

	protected function decodePayload(PacketSerializer $in) : void{
		$this->windowId = $in->getUnsignedVarInt();
		$count = $in->getUnsignedVarInt();
		for($i = 0; $i < $count; ++$i){
			$this->items[] = $in->getItemStackWrapper();
		}
		$this->containerName = FullContainerName::read($in);
		$this->dynamicContainerSize = $in->getUnsignedVarInt();
	}

	protected function encodePayload(PacketSerializer $out) : void{
		$out->putUnsignedVarInt($this->windowId);
		$out->putUnsignedVarInt(count($this->items));
		foreach($this->items as $item){
			$out->putItemStackWrapper($item);
		}
		$this->containerName->write($out);
		$out->putUnsignedVarInt($this->dynamicContainerSize);
	}

	public function handle(PacketHandlerInterface $handler) : bool{
		return $handler->handleInventoryContent($this);
	}
}
