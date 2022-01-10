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

class LevelEventGenericPacket extends DataPacket implements ClientboundPacket{
	public const NETWORK_ID = ProtocolInfo::LEVEL_EVENT_GENERIC_PACKET;

	private int $eventId;
	/** @phpstan-var CacheableNbt<\pocketmine\nbt\tag\CompoundTag> */
	private CacheableNbt $eventData;

	/**
	 * @generate-create-func
	 * @phpstan-param CacheableNbt<\pocketmine\nbt\tag\CompoundTag> $eventData
	 */
	public static function create(int $eventId, CacheableNbt $eventData) : self{
		$result = new self;
		$result->eventId = $eventId;
		$result->eventData = $eventData;
		return $result;
	}

	public function getEventId() : int{
		return $this->eventId;
	}

	/**
	 * @phpstan-return CacheableNbt<\pocketmine\nbt\tag\CompoundTag>
	 */
	public function getEventData() : CacheableNbt{
		return $this->eventData;
	}

	protected function decodePayload(PacketSerializer $in) : void{
		$this->eventId = $in->getVarInt();
		$this->eventData = new CacheableNbt($in->getNbtCompoundRoot());
	}

	protected function encodePayload(PacketSerializer $out) : void{
		$out->putVarInt($this->eventId);
		$out->put($this->eventData->getEncodedNbt());
	}

	public function handle(PacketHandlerInterface $handler) : bool{
		return $handler->handleLevelEventGeneric($this);
	}
}
