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

class TakeItemActorPacket extends DataPacket implements ClientboundPacket{
	public const NETWORK_ID = ProtocolInfo::TAKE_ITEM_ACTOR_PACKET;

	public int $takerActorRuntimeId;
	public int $itemActorRuntimeId;

	/**
	 * @generate-create-func
	 */
	public static function create(int $takerActorRuntimeId, int $itemActorRuntimeId) : self{
		$result = new self;
		$result->takerActorRuntimeId = $takerActorRuntimeId;
		$result->itemActorRuntimeId = $itemActorRuntimeId;
		return $result;
	}

	protected function decodePayload(PacketSerializer $in) : void{
		$this->itemActorRuntimeId = $in->getActorRuntimeId();
		$this->takerActorRuntimeId = $in->getActorRuntimeId();
	}

	protected function encodePayload(PacketSerializer $out) : void{
		$out->putActorRuntimeId($this->itemActorRuntimeId);
		$out->putActorRuntimeId($this->takerActorRuntimeId);
	}

	public function handle(PacketHandlerInterface $handler) : bool{
		return $handler->handleTakeItemActor($this);
	}
}
