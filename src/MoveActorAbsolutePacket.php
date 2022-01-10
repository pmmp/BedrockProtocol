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

class MoveActorAbsolutePacket extends DataPacket implements ClientboundPacket, ServerboundPacket{
	public const NETWORK_ID = ProtocolInfo::MOVE_ACTOR_ABSOLUTE_PACKET;

	public const FLAG_GROUND = 0x01;
	public const FLAG_TELEPORT = 0x02;
	public const FLAG_FORCE_MOVE_LOCAL_ENTITY = 0x04;

	public int $actorRuntimeId;
	public Vector3 $position;
	public float $pitch;
	public float $yaw;
	public float $headYaw; //always zero for non-mobs
	public int $flags = 0;

	/**
	 * @generate-create-func
	 */
	public static function create(int $actorRuntimeId, Vector3 $position, float $pitch, float $yaw, float $headYaw, int $flags) : self{
		$result = new self;
		$result->actorRuntimeId = $actorRuntimeId;
		$result->position = $position;
		$result->pitch = $pitch;
		$result->yaw = $yaw;
		$result->headYaw = $headYaw;
		$result->flags = $flags;
		return $result;
	}

	protected function decodePayload(PacketSerializer $in) : void{
		$this->actorRuntimeId = $in->getActorRuntimeId();
		$this->flags = $in->getByte();
		$this->position = $in->getVector3();
		$this->pitch = $in->getRotationByte();
		$this->yaw = $in->getRotationByte();
		$this->headYaw = $in->getRotationByte();
	}

	protected function encodePayload(PacketSerializer $out) : void{
		$out->putActorRuntimeId($this->actorRuntimeId);
		$out->putByte($this->flags);
		$out->putVector3($this->position);
		$out->putRotationByte($this->pitch);
		$out->putRotationByte($this->yaw);
		$out->putRotationByte($this->headYaw);
	}

	public function handle(PacketHandlerInterface $handler) : bool{
		return $handler->handleMoveActorAbsolute($this);
	}
}
