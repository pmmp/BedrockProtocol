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

class CorrectPlayerMovePredictionPacket extends DataPacket implements ClientboundPacket{
	public const NETWORK_ID = ProtocolInfo::CORRECT_PLAYER_MOVE_PREDICTION_PACKET;

	public const PREDICTION_TYPE_VEHICLE = 0;
	public const PREDICTION_TYPE_PLAYER = 1;

	private Vector3 $position;
	private Vector3 $delta;
	private bool $onGround;
	private int $tick;
	private int $predictionType;

	/**
	 * @generate-create-func
	 */
	public static function create(Vector3 $position, Vector3 $delta, bool $onGround, int $tick, int $predictionType) : self{
		$result = new self;
		$result->position = $position;
		$result->delta = $delta;
		$result->onGround = $onGround;
		$result->tick = $tick;
		$result->predictionType = $predictionType;
		return $result;
	}

	public function getPosition() : Vector3{ return $this->position; }

	public function getDelta() : Vector3{ return $this->delta; }

	public function isOnGround() : bool{ return $this->onGround; }

	public function getTick() : int{ return $this->tick; }

	public function getPredictionType() : int{ return $this->predictionType; }

	protected function decodePayload(PacketSerializer $in) : void{
		$this->position = $in->getVector3();
		$this->delta = $in->getVector3();
		$this->onGround = $in->getBool();
		$this->tick = $in->getUnsignedVarLong();
		$this->predictionType = $in->getByte();
	}

	protected function encodePayload(PacketSerializer $out) : void{
		$out->putVector3($this->position);
		$out->putVector3($this->delta);
		$out->putBool($this->onGround);
		$out->putUnsignedVarLong($this->tick);
		$out->putByte($this->predictionType);
	}

	public function handle(PacketHandlerInterface $handler) : bool{
		return $handler->handleCorrectPlayerMovePrediction($this);
	}
}
