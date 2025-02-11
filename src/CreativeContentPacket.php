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
use pocketmine\network\mcpe\protocol\types\inventory\CreativeGroupEntry;
use pocketmine\network\mcpe\protocol\types\inventory\CreativeItemEntry;
use function count;

class CreativeContentPacket extends DataPacket implements ClientboundPacket{
	public const NETWORK_ID = ProtocolInfo::CREATIVE_CONTENT_PACKET;

	/** @var CreativeGroupEntry[] */
	private array $groups;
	/** @var CreativeItemEntry[] */
	private array $items;

	/**
	 * @generate-create-func
	 * @param CreativeGroupEntry[] $groups
	 * @param CreativeItemEntry[]  $items
	 */
	public static function create(array $groups, array $items) : self{
		$result = new self;
		$result->groups = $groups;
		$result->items = $items;
		return $result;
	}

	/** @return CreativeGroupEntry[] */
	public function getGroups() : array{ return $this->groups; }

	/** @return CreativeItemEntry[] */
	public function getItems() : array{ return $this->items; }

	protected function decodePayload(PacketSerializer $in) : void{
		$this->groups = [];
		for($i = 0, $len = $in->getUnsignedVarInt(); $i < $len; ++$i){
			$this->groups[] = CreativeGroupEntry::read($in);
		}

		$this->items = [];
		for($i = 0, $len = $in->getUnsignedVarInt(); $i < $len; ++$i){
			$this->items[] = CreativeItemEntry::read($in);
		}
	}

	protected function encodePayload(PacketSerializer $out) : void{
		$out->putUnsignedVarInt(count($this->groups));
		foreach($this->groups as $entry){
			$entry->write($out);
		}

		$out->putUnsignedVarInt(count($this->items));
		foreach($this->items as $entry){
			$entry->write($out);
		}
	}

	public function handle(PacketHandlerInterface $handler) : bool{
		return $handler->handleCreativeContent($this);
	}
}
