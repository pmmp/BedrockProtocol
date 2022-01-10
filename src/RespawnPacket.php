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

use pocketmine\math\Vector3;
use pocketmine\network\mcpe\protocol\serializer\PacketSerializer;

class RespawnPacket extends DataPacket implements ClientboundPacket, ServerboundPacket{
	public const NETWORK_ID = ProtocolInfo::RESPAWN_PACKET;

	public const SEARCHING_FOR_SPAWN = 0;
	public const READY_TO_SPAWN = 1;
	public const CLIENT_READY_TO_SPAWN = 2;

	public Vector3 $position;
	public int $respawnState = self::SEARCHING_FOR_SPAWN;
	public int $actorRuntimeId;

	/**
	 * @generate-create-func
	 */
	public static function create(Vector3 $position, int $respawnState, int $actorRuntimeId) : self{
		$result = new self;
		$result->position = $position;
		$result->respawnState = $respawnState;
		$result->actorRuntimeId = $actorRuntimeId;
		return $result;
	}

	protected function decodePayload(PacketSerializer $in) : void{
		$this->position = $in->getVector3();
		$this->respawnState = $in->getByte();
		$this->actorRuntimeId = $in->getActorRuntimeId();
	}

	protected function encodePayload(PacketSerializer $out) : void{
		$out->putVector3($this->position);
		$out->putByte($this->respawnState);
		$out->putActorRuntimeId($this->actorRuntimeId);
	}

	public function handle(PacketHandlerInterface $handler) : bool{
		return $handler->handleRespawn($this);
	}
}
