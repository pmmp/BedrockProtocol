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

class SetActorMotionPacket extends DataPacket implements ClientboundPacket, ServerboundPacket{
	public const NETWORK_ID = ProtocolInfo::SET_ACTOR_MOTION_PACKET;

	public int $actorRuntimeId;
	public Vector3 $motion;
	public int $tick;

	/**
	 * @generate-create-func
	 */
	public static function create(int $actorRuntimeId, Vector3 $motion, int $tick) : self{
		$result = new self;
		$result->actorRuntimeId = $actorRuntimeId;
		$result->motion = $motion;
		$result->tick = $tick;
		return $result;
	}

	protected function decodePayload(PacketSerializer $in) : void{
		$this->actorRuntimeId = $in->getActorRuntimeId();
		$this->motion = $in->getVector3();
		$this->tick = $in->getUnsignedVarLong();
	}

	protected function encodePayload(PacketSerializer $out) : void{
		$out->putActorRuntimeId($this->actorRuntimeId);
		$out->putVector3($this->motion);
		$out->putUnsignedVarLong($this->tick);
	}

	public function handle(PacketHandlerInterface $handler) : bool{
		return $handler->handleSetActorMotion($this);
	}
}
