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

class MovePlayerPacket extends DataPacket implements ClientboundPacket, ServerboundPacket{
	public const NETWORK_ID = ProtocolInfo::MOVE_PLAYER_PACKET;

	public const MODE_NORMAL = 0;
	public const MODE_RESET = 1;
	public const MODE_TELEPORT = 2;
	public const MODE_PITCH = 3; //facepalm Mojang

	public int $actorRuntimeId;
	public Vector3 $position;
	public float $pitch;
	public float $yaw;
	public float $headYaw;
	public int $mode = self::MODE_NORMAL;
	public bool $onGround = false; //TODO
	public int $ridingActorRuntimeId = 0;
	public int $teleportCause = 0;
	public int $teleportItem = 0;
	public int $tick = 0;

	/**
	 * @generate-create-func
	 */
	public static function create(
		int $actorRuntimeId,
		Vector3 $position,
		float $pitch,
		float $yaw,
		float $headYaw,
		int $mode,
		bool $onGround,
		int $ridingActorRuntimeId,
		int $teleportCause,
		int $teleportItem,
		int $tick,
	) : self{
		$result = new self;
		$result->actorRuntimeId = $actorRuntimeId;
		$result->position = $position;
		$result->pitch = $pitch;
		$result->yaw = $yaw;
		$result->headYaw = $headYaw;
		$result->mode = $mode;
		$result->onGround = $onGround;
		$result->ridingActorRuntimeId = $ridingActorRuntimeId;
		$result->teleportCause = $teleportCause;
		$result->teleportItem = $teleportItem;
		$result->tick = $tick;
		return $result;
	}

	public static function simple(
		int $actorRuntimeId,
		Vector3 $position,
		float $pitch,
		float $yaw,
		float $headYaw,
		int $mode,
		bool $onGround,
		int $ridingActorRuntimeId,
		int $tick,
	) : self{
		return self::create($actorRuntimeId, $position, $pitch, $yaw, $headYaw, $mode, $onGround, $ridingActorRuntimeId, 0, 0, $tick);
	}

	protected function decodePayload(PacketSerializer $in) : void{
		$this->actorRuntimeId = $in->getActorRuntimeId();
		$this->position = $in->getVector3();
		$this->pitch = $in->getLFloat();
		$this->yaw = $in->getLFloat();
		$this->headYaw = $in->getLFloat();
		$this->mode = $in->getByte();
		$this->onGround = $in->getBool();
		$this->ridingActorRuntimeId = $in->getActorRuntimeId();
		if($this->mode === MovePlayerPacket::MODE_TELEPORT){
			$this->teleportCause = $in->getLInt();
			$this->teleportItem = $in->getLInt();
		}
		$this->tick = $in->getUnsignedVarLong();
	}

	protected function encodePayload(PacketSerializer $out) : void{
		$out->putActorRuntimeId($this->actorRuntimeId);
		$out->putVector3($this->position);
		$out->putLFloat($this->pitch);
		$out->putLFloat($this->yaw);
		$out->putLFloat($this->headYaw); //TODO
		$out->putByte($this->mode);
		$out->putBool($this->onGround);
		$out->putActorRuntimeId($this->ridingActorRuntimeId);
		if($this->mode === MovePlayerPacket::MODE_TELEPORT){
			$out->putLInt($this->teleportCause);
			$out->putLInt($this->teleportItem);
		}
		$out->putUnsignedVarLong($this->tick);
	}

	public function handle(PacketHandlerInterface $handler) : bool{
		return $handler->handleMovePlayer($this);
	}
}
