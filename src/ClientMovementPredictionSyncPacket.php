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

use pocketmine\network\mcpe\protocol\serializer\BitSet;
use pocketmine\network\mcpe\protocol\serializer\PacketSerializer;

class ClientMovementPredictionSyncPacket extends DataPacket implements ServerboundPacket{
	public const NETWORK_ID = ProtocolInfo::CLIENT_MOVEMENT_PREDICTION_SYNC_PACKET;

	public const FLAG_LENGTH = 123;

	private BitSet $flags;

	private float $scale;
	private float $width;
	private float $height;

	private float $movementSpeed;
	private float $underwaterMovementSpeed;
	private float $lavaMovementSpeed;
	private float $jumpStrength;
	private float $health;
	private float $hunger;

	private int $actorUniqueId;
	private bool $actorFlyingState;

	/**
	 * @generate-create-func
	 */
	private static function internalCreate(
		BitSet $flags,
		float $scale,
		float $width,
		float $height,
		float $movementSpeed,
		float $underwaterMovementSpeed,
		float $lavaMovementSpeed,
		float $jumpStrength,
		float $health,
		float $hunger,
		int $actorUniqueId,
		bool $actorFlyingState,
	) : self{
		$result = new self;
		$result->flags = $flags;
		$result->scale = $scale;
		$result->width = $width;
		$result->height = $height;
		$result->movementSpeed = $movementSpeed;
		$result->underwaterMovementSpeed = $underwaterMovementSpeed;
		$result->lavaMovementSpeed = $lavaMovementSpeed;
		$result->jumpStrength = $jumpStrength;
		$result->health = $health;
		$result->hunger = $hunger;
		$result->actorUniqueId = $actorUniqueId;
		$result->actorFlyingState = $actorFlyingState;
		return $result;
	}

	public static function create(
		BitSet $flags,
		float $scale,
		float $width,
		float $height,
		float $movementSpeed,
		float $underwaterMovementSpeed,
		float $lavaMovementSpeed,
		float $jumpStrength,
		float $health,
		float $hunger,
		int $actorUniqueId,
		bool $actorFlyingState,
	) : self{
		if($flags->getLength() !== self::FLAG_LENGTH){
			throw new \InvalidArgumentException("Input flags must be " . self::FLAG_LENGTH . " bits long");
		}

		return self::internalCreate($flags, $scale, $width, $height, $movementSpeed, $underwaterMovementSpeed, $lavaMovementSpeed, $jumpStrength, $health, $hunger, $actorUniqueId, $actorFlyingState);
	}

	public function getFlags() : BitSet{ return $this->flags; }

	public function getScale() : float{ return $this->scale; }

	public function getWidth() : float{ return $this->width; }

	public function getHeight() : float{ return $this->height; }

	public function getMovementSpeed() : float{ return $this->movementSpeed; }

	public function getUnderwaterMovementSpeed() : float{ return $this->underwaterMovementSpeed; }

	public function getLavaMovementSpeed() : float{ return $this->lavaMovementSpeed; }

	public function getJumpStrength() : float{ return $this->jumpStrength; }

	public function getHealth() : float{ return $this->health; }

	public function getHunger() : float{ return $this->hunger; }

	public function getActorUniqueId() : int{ return $this->actorUniqueId; }

	public function getActorFlyingState() : bool{ return $this->actorFlyingState; }

	protected function decodePayload(PacketSerializer $in) : void{
		$this->flags = BitSet::read($in, self::FLAG_LENGTH);
		$this->scale = $in->getLFloat();
		$this->width = $in->getLFloat();
		$this->height = $in->getLFloat();
		$this->movementSpeed = $in->getLFloat();
		$this->underwaterMovementSpeed = $in->getLFloat();
		$this->lavaMovementSpeed = $in->getLFloat();
		$this->jumpStrength = $in->getLFloat();
		$this->health = $in->getLFloat();
		$this->hunger = $in->getLFloat();
		$this->actorUniqueId = $in->getActorUniqueId();
		$this->actorFlyingState = $in->getBool();
	}

	protected function encodePayload(PacketSerializer $out) : void{
		$this->flags->write($out);
		$out->putLFloat($this->scale);
		$out->putLFloat($this->width);
		$out->putLFloat($this->height);
		$out->putLFloat($this->movementSpeed);
		$out->putLFloat($this->underwaterMovementSpeed);
		$out->putLFloat($this->lavaMovementSpeed);
		$out->putLFloat($this->jumpStrength);
		$out->putLFloat($this->health);
		$out->putLFloat($this->hunger);
		$out->putActorUniqueId($this->actorUniqueId);
		$out->putBool($this->actorFlyingState);
	}

	public function handle(PacketHandlerInterface $handler) : bool{
		return $handler->handleClientMovementPredictionSync($this);
	}
}
