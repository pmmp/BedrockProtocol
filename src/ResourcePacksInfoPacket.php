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
use pmmp\encoding\LE;
use pocketmine\network\mcpe\protocol\serializer\CommonTypes;
use pocketmine\network\mcpe\protocol\types\resourcepacks\ResourcePackInfoEntry;
use Ramsey\Uuid\UuidInterface;
use function count;

class ResourcePacksInfoPacket extends DataPacket implements ClientboundPacket{
	public const NETWORK_ID = ProtocolInfo::RESOURCE_PACKS_INFO_PACKET;

	/** @var ResourcePackInfoEntry[] */
	public array $resourcePackEntries = [];
	public bool $mustAccept = false; //if true, forces client to choose between accepting packs or being disconnected
	public bool $hasAddons = false;
	public bool $hasScripts = false; //if true, causes disconnect for any platform that doesn't support scripts yet
	private UuidInterface $worldTemplateId;
	private string $worldTemplateVersion;
	private bool $forceDisableVibrantVisuals;

	/**
	 * @generate-create-func
	 * @param ResourcePackInfoEntry[] $resourcePackEntries
	 */
	public static function create(
		array $resourcePackEntries,
		bool $mustAccept,
		bool $hasAddons,
		bool $hasScripts,
		UuidInterface $worldTemplateId,
		string $worldTemplateVersion,
		bool $forceDisableVibrantVisuals,
	) : self{
		$result = new self;
		$result->resourcePackEntries = $resourcePackEntries;
		$result->mustAccept = $mustAccept;
		$result->hasAddons = $hasAddons;
		$result->hasScripts = $hasScripts;
		$result->worldTemplateId = $worldTemplateId;
		$result->worldTemplateVersion = $worldTemplateVersion;
		$result->forceDisableVibrantVisuals = $forceDisableVibrantVisuals;
		return $result;
	}

	public function getWorldTemplateId() : UuidInterface{ return $this->worldTemplateId; }

	public function getWorldTemplateVersion() : string{ return $this->worldTemplateVersion; }

	public function isForceDisablingVibrantVisuals() : bool{ return $this->forceDisableVibrantVisuals; }

	protected function decodePayload(ByteBufferReader $in) : void{
		$this->mustAccept = CommonTypes::getBool($in);
		$this->hasAddons = CommonTypes::getBool($in);
		$this->hasScripts = CommonTypes::getBool($in);
		$this->forceDisableVibrantVisuals = CommonTypes::getBool($in);
		$this->worldTemplateId = CommonTypes::getUUID($in);
		$this->worldTemplateVersion = CommonTypes::getString($in);

		$resourcePackCount = LE::readUnsignedShort($in);
		while($resourcePackCount-- > 0){
			$this->resourcePackEntries[] = ResourcePackInfoEntry::read($in);
		}
	}

	protected function encodePayload(ByteBufferWriter $out) : void{
		CommonTypes::putBool($out, $this->mustAccept);
		CommonTypes::putBool($out, $this->hasAddons);
		CommonTypes::putBool($out, $this->hasScripts);
		CommonTypes::putBool($out, $this->forceDisableVibrantVisuals);
		CommonTypes::putUUID($out, $this->worldTemplateId);
		CommonTypes::putString($out, $this->worldTemplateVersion);
		LE::writeUnsignedShort($out, count($this->resourcePackEntries));
		foreach($this->resourcePackEntries as $entry){
			$entry->write($out);
		}
	}

	public function handle(PacketHandlerInterface $handler) : bool{
		return $handler->handleResourcePacksInfo($this);
	}
}
