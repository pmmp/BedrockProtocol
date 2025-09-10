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

use pmmp\encoding\Byte;
use pmmp\encoding\ByteBufferReader;
use pmmp\encoding\ByteBufferWriter;
use pmmp\encoding\LE;
use pmmp\encoding\VarInt;
use pocketmine\math\Vector3;
use pocketmine\network\mcpe\protocol\serializer\CommonTypes;

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

	protected function decodePayload(ByteBufferReader $in) : void{
		$this->actorRuntimeId = CommonTypes::getActorRuntimeId($in);
		$this->position = CommonTypes::getVector3($in);
		$this->pitch = LE::readFloat($in);
		$this->yaw = LE::readFloat($in);
		$this->headYaw = LE::readFloat($in);
		$this->mode = Byte::readUnsigned($in);
		$this->onGround = CommonTypes::getBool($in);
		$this->ridingActorRuntimeId = CommonTypes::getActorRuntimeId($in);
		if($this->mode === MovePlayerPacket::MODE_TELEPORT){
			$this->teleportCause = LE::readSignedInt($in);
			$this->teleportItem = LE::readSignedInt($in);
		}
		$this->tick = VarInt::readUnsignedLong($in);
	}

	protected function encodePayload(ByteBufferWriter $out) : void{
		CommonTypes::putActorRuntimeId($out, $this->actorRuntimeId);
		CommonTypes::putVector3($out, $this->position);
		LE::writeFloat($out, $this->pitch);
		LE::writeFloat($out, $this->yaw);
		LE::writeFloat($out, $this->headYaw); //TODO
		Byte::writeUnsigned($out, $this->mode);
		CommonTypes::putBool($out, $this->onGround);
		CommonTypes::putActorRuntimeId($out, $this->ridingActorRuntimeId);
		if($this->mode === MovePlayerPacket::MODE_TELEPORT){
			LE::writeSignedInt($out, $this->teleportCause);
			LE::writeSignedInt($out, $this->teleportItem);
		}
		VarInt::writeUnsignedLong($out, $this->tick);
	}

	public function handle(PacketHandlerInterface $handler) : bool{
		return $handler->handleMovePlayer($this);
	}
}
