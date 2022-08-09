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
use pocketmine\network\mcpe\protocol\types\FeatureRegistryPacketEntry;
use function count;

/**
 * Syncs world generator settings from server to client, for client-sided chunk generation.
 */
class FeatureRegistryPacket extends DataPacket implements ClientboundPacket{
	public const NETWORK_ID = ProtocolInfo::FEATURE_REGISTRY_PACKET;

	/** @var FeatureRegistryPacketEntry[] */
	private array $entries;

	/**
	 * @generate-create-func
	 * @param FeatureRegistryPacketEntry[] $entries
	 */
	public static function create(array $entries) : self{
		$result = new self;
		$result->entries = $entries;
		return $result;
	}

	/** @return FeatureRegistryPacketEntry[] */
	public function getEntries() : array{ return $this->entries; }

	protected function decodePayload(PacketSerializer $in) : void{
		for($this->entries = [], $i = 0, $count = $in->getUnsignedVarInt(); $i < $count; $i++){
			$this->entries[] = FeatureRegistryPacketEntry::read($in);
		}
	}

	protected function encodePayload(PacketSerializer $out) : void{
		$out->putUnsignedVarInt(count($this->entries));
		foreach($this->entries as $entry){
			$entry->write($out);
		}
	}

	public function handle(PacketHandlerInterface $handler) : bool{
		return $handler->handleFeatureRegistry($this);
	}
}
