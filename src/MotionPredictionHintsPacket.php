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

class MotionPredictionHintsPacket extends DataPacket implements ClientboundPacket{
	public const NETWORK_ID = ProtocolInfo::MOTION_PREDICTION_HINTS_PACKET;

	private int $actorRuntimeId;
	private Vector3 $motion;
	private bool $onGround;

	/**
	 * @generate-create-func
	 */
	public static function create(int $actorRuntimeId, Vector3 $motion, bool $onGround) : self{
		$result = new self;
		$result->actorRuntimeId = $actorRuntimeId;
		$result->motion = $motion;
		$result->onGround = $onGround;
		return $result;
	}

	public function getActorRuntimeId() : int{ return $this->actorRuntimeId; }

	public function getMotion() : Vector3{ return $this->motion; }

	public function isOnGround() : bool{ return $this->onGround; }

	protected function decodePayload(PacketSerializer $in) : void{
		$this->actorRuntimeId = $in->getActorRuntimeId();
		$this->motion = $in->getVector3();
		$this->onGround = $in->getBool();
	}

	protected function encodePayload(PacketSerializer $out) : void{
		$out->putActorRuntimeId($this->actorRuntimeId);
		$out->putVector3($this->motion);
		$out->putBool($this->onGround);
	}

	public function handle(PacketHandlerInterface $handler) : bool{
		return $handler->handleMotionPredictionHints($this);
	}
}
