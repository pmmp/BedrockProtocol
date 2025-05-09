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
use pocketmine\network\mcpe\protocol\types\PlayerLocationType;

class PlayerLocationPacket extends DataPacket implements ClientboundPacket{
	public const NETWORK_ID = ProtocolInfo::PLAYER_LOCATION_PACKET;

	private PlayerLocationType $type;
	private int $actorUniqueId;
	private ?Vector3 $position;

	/**
	 * @generate-create-func
	 */
	private static function create(PlayerLocationType $type, int $actorUniqueId, ?Vector3 $position) : self{
		$result = new self;
		$result->type = $type;
		$result->actorUniqueId = $actorUniqueId;
		$result->position = $position;
		return $result;
	}

	public static function createCoordinates(int $actorUniqueId, Vector3 $position) : self{
		return self::create(PlayerLocationType::PLAYER_LOCATION_COORDINATES, $actorUniqueId, $position);
	}

	public static function createHide(int $actorUniqueId) : self{
		return self::create(PlayerLocationType::PLAYER_LOCATION_HIDE, $actorUniqueId, null);
	}

	public function getType() : PlayerLocationType{ return $this->type; }

	public function getActorUniqueId() : int{ return $this->actorUniqueId; }

	public function getPosition() : ?Vector3{ return $this->position; }

	protected function decodePayload(PacketSerializer $in) : void{
		$this->type = PlayerLocationType::fromPacket($in->getLInt());
		$this->actorUniqueId = $in->getActorUniqueId();

		if($this->type === PlayerLocationType::PLAYER_LOCATION_COORDINATES){
			$this->position = $in->getVector3();
		}
	}

	protected function encodePayload(PacketSerializer $out) : void{
		$out->putLInt($this->type->value);
		$out->putActorUniqueId($this->actorUniqueId);

		if($this->type === PlayerLocationType::PLAYER_LOCATION_COORDINATES){
			if($this->position === null){ // this should never be the case
				throw new \LogicException("PlayerLocationPacket with type PLAYER_LOCATION_COORDINATES require a position to be provided");
			}
			$out->putVector3($this->position);
		}
	}

	public function handle(PacketHandlerInterface $handler) : bool{
		return $handler->handlePlayerLocation($this);
	}
}
