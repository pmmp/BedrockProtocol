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
use pocketmine\network\mcpe\protocol\types\ItemTypeEntry;
use function count;

class ItemRegistryPacket extends DataPacket implements ClientboundPacket{
	public const NETWORK_ID = ProtocolInfo::ITEM_REGISTRY_PACKET;

	/**
	 * @var ItemTypeEntry[]
	 * @phpstan-var list<ItemTypeEntry>
	 */
	private array $entries;

	/**
	 * @generate-create-func
	 * @param ItemTypeEntry[] $entries
	 * @phpstan-param list<ItemTypeEntry> $entries
	 */
	public static function create(array $entries) : self{
		$result = new self;
		$result->entries = $entries;
		return $result;
	}

	/**
	 * @return ItemTypeEntry[]
	 * @phpstan-return list<ItemTypeEntry>
	 */
	public function getEntries() : array{ return $this->entries; }

	protected function decodePayload(PacketSerializer $in) : void{
		$this->entries = [];
		for($i = 0, $len = $in->getUnsignedVarInt(); $i < $len; ++$i){
			$stringId = $in->getString();
			$numericId = $in->getSignedLShort();
			$isComponentBased = $in->getBool();
			$version = $in->getVarInt();
			$nbt = $in->getNbtCompoundRoot();
			$this->entries[] = new ItemTypeEntry($stringId, $numericId, $isComponentBased, $version, new CacheableNbt($nbt));
		}
	}

	protected function encodePayload(PacketSerializer $out) : void{
		$out->putUnsignedVarInt(count($this->entries));
		foreach($this->entries as $entry){
			$out->putString($entry->getStringId());
			$out->putLShort($entry->getNumericId());
			$out->putBool($entry->isComponentBased());
			$out->putVarInt($entry->getVersion());
			$out->put($entry->getComponentNbt()->getEncodedNbt());
		}
	}

	public function handle(PacketHandlerInterface $handler) : bool{
		return $handler->handleItemRegistry($this);
	}
}
