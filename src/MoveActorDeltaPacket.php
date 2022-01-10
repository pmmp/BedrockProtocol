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
use pocketmine\utils\BinaryDataException;

class MoveActorDeltaPacket extends DataPacket implements ClientboundPacket{
	public const NETWORK_ID = ProtocolInfo::MOVE_ACTOR_DELTA_PACKET;

	public const FLAG_HAS_X = 0x01;
	public const FLAG_HAS_Y = 0x02;
	public const FLAG_HAS_Z = 0x04;
	public const FLAG_HAS_PITCH = 0x08;
	public const FLAG_HAS_YAW = 0x10;
	public const FLAG_HAS_HEAD_YAW = 0x20;
	public const FLAG_GROUND = 0x40;
	public const FLAG_TELEPORT = 0x80;
	public const FLAG_FORCE_MOVE_LOCAL_ENTITY = 0x100;

	public int $actorRuntimeId;
	public int $flags;
	public float $xPos = 0;
	public float $yPos = 0;
	public float $zPos = 0;
	public float $pitch = 0.0;
	public float $yaw = 0.0;
	public float $headYaw = 0.0;

	/**
	 * @throws BinaryDataException
	 */
	private function maybeReadCoord(int $flag, PacketSerializer $in) : float{
		if(($this->flags & $flag) !== 0){
			return $in->getLFloat();
		}
		return 0;
	}

	/**
	 * @throws BinaryDataException
	 */
	private function maybeReadRotation(int $flag, PacketSerializer $in) : float{
		if(($this->flags & $flag) !== 0){
			return $in->getRotationByte();
		}
		return 0.0;
	}

	protected function decodePayload(PacketSerializer $in) : void{
		$this->actorRuntimeId = $in->getActorRuntimeId();
		$this->flags = $in->getLShort();
		$this->xPos = $this->maybeReadCoord(self::FLAG_HAS_X, $in);
		$this->yPos = $this->maybeReadCoord(self::FLAG_HAS_Y, $in);
		$this->zPos = $this->maybeReadCoord(self::FLAG_HAS_Z, $in);
		$this->pitch = $this->maybeReadRotation(self::FLAG_HAS_PITCH, $in);
		$this->yaw = $this->maybeReadRotation(self::FLAG_HAS_YAW, $in);
		$this->headYaw = $this->maybeReadRotation(self::FLAG_HAS_HEAD_YAW, $in);
	}

	private function maybeWriteCoord(int $flag, float $val, PacketSerializer $out) : void{
		if(($this->flags & $flag) !== 0){
			$out->putLFloat($val);
		}
	}

	private function maybeWriteRotation(int $flag, float $val, PacketSerializer $out) : void{
		if(($this->flags & $flag) !== 0){
			$out->putRotationByte($val);
		}
	}

	protected function encodePayload(PacketSerializer $out) : void{
		$out->putActorRuntimeId($this->actorRuntimeId);
		$out->putLShort($this->flags);
		$this->maybeWriteCoord(self::FLAG_HAS_X, $this->xPos, $out);
		$this->maybeWriteCoord(self::FLAG_HAS_Y, $this->yPos, $out);
		$this->maybeWriteCoord(self::FLAG_HAS_Z, $this->zPos, $out);
		$this->maybeWriteRotation(self::FLAG_HAS_PITCH, $this->pitch, $out);
		$this->maybeWriteRotation(self::FLAG_HAS_YAW, $this->yaw, $out);
		$this->maybeWriteRotation(self::FLAG_HAS_HEAD_YAW, $this->headYaw, $out);
	}

	public function handle(PacketHandlerInterface $handler) : bool{
		return $handler->handleMoveActorDelta($this);
	}
}
