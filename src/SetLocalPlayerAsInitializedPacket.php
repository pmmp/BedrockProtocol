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

class SetLocalPlayerAsInitializedPacket extends DataPacket implements ServerboundPacket{
	public const NETWORK_ID = ProtocolInfo::SET_LOCAL_PLAYER_AS_INITIALIZED_PACKET;

	public int $actorRuntimeId;

	/**
	 * @generate-create-func
	 */
	public static function create(int $actorRuntimeId) : self{
		$result = new self;
		$result->actorRuntimeId = $actorRuntimeId;
		return $result;
	}

	protected function decodePayload(PacketSerializer $in) : void{
		$this->actorRuntimeId = $in->getActorRuntimeId();
	}

	protected function encodePayload(PacketSerializer $out) : void{
		$out->putActorRuntimeId($this->actorRuntimeId);
	}

	public function handle(PacketHandlerInterface $handler) : bool{
		return $handler->handleSetLocalPlayerAsInitialized($this);
	}
}
