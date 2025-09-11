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
use pocketmine\math\Vector3;
use pocketmine\network\mcpe\protocol\serializer\CommonTypes;

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

	protected function decodePayload(ByteBufferReader $in) : void{
		$this->actorRuntimeId = CommonTypes::getActorRuntimeId($in);
		$this->flags = Byte::readUnsigned($in);
		$this->position = CommonTypes::getVector3($in);
		$this->pitch = CommonTypes::getRotationByte($in);
		$this->yaw = CommonTypes::getRotationByte($in);
		$this->headYaw = CommonTypes::getRotationByte($in);
	}

	protected function encodePayload(ByteBufferWriter $out) : void{
		CommonTypes::putActorRuntimeId($out, $this->actorRuntimeId);
		Byte::writeUnsigned($out, $this->flags);
		CommonTypes::putVector3($out, $this->position);
		CommonTypes::putRotationByte($out, $this->pitch);
		CommonTypes::putRotationByte($out, $this->yaw);
		CommonTypes::putRotationByte($out, $this->headYaw);
	}

	public function handle(PacketHandlerInterface $handler) : bool{
		return $handler->handleMoveActorAbsolute($this);
	}
}
