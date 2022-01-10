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
use pocketmine\network\mcpe\protocol\types\ItemComponentPacketEntry;
use function count;

class ItemComponentPacket extends DataPacket implements ClientboundPacket{
	public const NETWORK_ID = ProtocolInfo::ITEM_COMPONENT_PACKET;

	/**
	 * @var ItemComponentPacketEntry[]
	 * @phpstan-var list<ItemComponentPacketEntry>
	 */
	private array $entries;

	/**
	 * @generate-create-func
	 * @param ItemComponentPacketEntry[] $entries
	 * @phpstan-param list<ItemComponentPacketEntry> $entries
	 */
	public static function create(array $entries) : self{
		$result = new self;
		$result->entries = $entries;
		return $result;
	}

	/**
	 * @return ItemComponentPacketEntry[]
	 * @phpstan-return list<ItemComponentPacketEntry>
	 */
	public function getEntries() : array{ return $this->entries; }

	protected function decodePayload(PacketSerializer $in) : void{
		$this->entries = [];
		for($i = 0, $len = $in->getUnsignedVarInt(); $i < $len; ++$i){
			$name = $in->getString();
			$nbt = $in->getNbtCompoundRoot();
			$this->entries[] = new ItemComponentPacketEntry($name, new CacheableNbt($nbt));
		}
	}

	protected function encodePayload(PacketSerializer $out) : void{
		$out->putUnsignedVarInt(count($this->entries));
		foreach($this->entries as $entry){
			$out->putString($entry->getName());
			$out->put($entry->getComponentNbt()->getEncodedNbt());
		}
	}

	public function handle(PacketHandlerInterface $handler) : bool{
		return $handler->handleItemComponent($this);
	}
}
