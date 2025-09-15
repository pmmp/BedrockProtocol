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
use pocketmine\math\Vector2;
use pocketmine\math\Vector3;
use pocketmine\network\mcpe\protocol\serializer\CommonTypes;

class CorrectPlayerMovePredictionPacket extends DataPacket implements ClientboundPacket{
	public const NETWORK_ID = ProtocolInfo::CORRECT_PLAYER_MOVE_PREDICTION_PACKET;

	public const PREDICTION_TYPE_PLAYER = 0;
	public const PREDICTION_TYPE_VEHICLE = 1;

	private Vector3 $position;
	private Vector3 $delta;
	private bool $onGround;
	private int $tick;
	private int $predictionType;
	private Vector2 $vehicleRotation;
	private ?float $vehicleAngularVelocity;

	/**
	 * @generate-create-func
	 */
	public static function create(
		Vector3 $position,
		Vector3 $delta,
		bool $onGround,
		int $tick,
		int $predictionType,
		Vector2 $vehicleRotation,
		?float $vehicleAngularVelocity,
	) : self{
		$result = new self;
		$result->position = $position;
		$result->delta = $delta;
		$result->onGround = $onGround;
		$result->tick = $tick;
		$result->predictionType = $predictionType;
		$result->vehicleRotation = $vehicleRotation;
		$result->vehicleAngularVelocity = $vehicleAngularVelocity;
		return $result;
	}

	public function getPosition() : Vector3{ return $this->position; }

	public function getDelta() : Vector3{ return $this->delta; }

	public function isOnGround() : bool{ return $this->onGround; }

	public function getTick() : int{ return $this->tick; }

	public function getPredictionType() : int{ return $this->predictionType; }

	public function getVehicleRotation() : Vector2{ return $this->vehicleRotation; }

	public function getVehicleAngularVelocity() : ?float{ return $this->vehicleAngularVelocity; }

	protected function decodePayload(ByteBufferReader $in) : void{
		$this->predictionType = Byte::readUnsigned($in);
		$this->position = CommonTypes::getVector3($in);
		$this->delta = CommonTypes::getVector3($in);
		$this->vehicleRotation = new Vector2(LE::readFloat($in), LE::readFloat($in));
		$this->vehicleAngularVelocity = CommonTypes::readOptional($in, LE::readFloat(...));
		$this->onGround = CommonTypes::getBool($in);
		$this->tick = VarInt::readUnsignedLong($in);
	}

	protected function encodePayload(ByteBufferWriter $out) : void{
		Byte::writeUnsigned($out, $this->predictionType);
		CommonTypes::putVector3($out, $this->position);
		CommonTypes::putVector3($out, $this->delta);
		LE::writeFloat($out, $this->vehicleRotation->getX());
		LE::writeFloat($out, $this->vehicleRotation->getY());
		CommonTypes::writeOptional($out, $this->vehicleAngularVelocity, LE::writeFloat(...));
		CommonTypes::putBool($out, $this->onGround);
		VarInt::writeUnsignedLong($out, $this->tick);
	}

	public function handle(PacketHandlerInterface $handler) : bool{
		return $handler->handleCorrectPlayerMovePrediction($this);
	}
}
