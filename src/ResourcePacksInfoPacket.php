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
use pocketmine\network\mcpe\protocol\types\resourcepacks\BehaviorPackInfoEntry;
use pocketmine\network\mcpe\protocol\types\resourcepacks\ResourcePackInfoEntry;
use function count;

class ResourcePacksInfoPacket extends DataPacket implements ClientboundPacket{
	public const NETWORK_ID = ProtocolInfo::RESOURCE_PACKS_INFO_PACKET;

	/** @var ResourcePackInfoEntry[] */
	public array $resourcePackEntries = [];
	/** @var BehaviorPackInfoEntry[] */
	public array $behaviorPackEntries = [];
	public bool $mustAccept = false; //if true, forces client to choose between accepting packs or being disconnected
	public bool $hasScripts = false; //if true, causes disconnect for any platform that doesn't support scripts yet
	public bool $forceServerPacks = false;

	/**
	 * @generate-create-func
	 * @param ResourcePackInfoEntry[] $resourcePackEntries
	 * @param BehaviorPackInfoEntry[] $behaviorPackEntries
	 */
	public static function create(array $resourcePackEntries, array $behaviorPackEntries, bool $mustAccept, bool $hasScripts, bool $forceServerPacks) : self{
		$result = new self;
		$result->resourcePackEntries = $resourcePackEntries;
		$result->behaviorPackEntries = $behaviorPackEntries;
		$result->mustAccept = $mustAccept;
		$result->hasScripts = $hasScripts;
		$result->forceServerPacks = $forceServerPacks;
		return $result;
	}

	protected function decodePayload(PacketSerializer $in) : void{
		$this->mustAccept = $in->getBool();
		$this->hasScripts = $in->getBool();
		$this->forceServerPacks = $in->getBool();
		$behaviorPackCount = $in->getLShort();
		while($behaviorPackCount-- > 0){
			$this->behaviorPackEntries[] = BehaviorPackInfoEntry::read($in);
		}

		$resourcePackCount = $in->getLShort();
		while($resourcePackCount-- > 0){
			$this->resourcePackEntries[] = ResourcePackInfoEntry::read($in);
		}
	}

	protected function encodePayload(PacketSerializer $out) : void{
		$out->putBool($this->mustAccept);
		$out->putBool($this->hasScripts);
		$out->putBool($this->forceServerPacks);
		$out->putLShort(count($this->behaviorPackEntries));
		foreach($this->behaviorPackEntries as $entry){
			$entry->write($out);
		}
		$out->putLShort(count($this->resourcePackEntries));
		foreach($this->resourcePackEntries as $entry){
			$entry->write($out);
		}
	}

	public function handle(PacketHandlerInterface $handler) : bool{
		return $handler->handleResourcePacksInfo($this);
	}
}
