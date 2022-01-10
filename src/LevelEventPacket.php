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
use pocketmine\network\mcpe\protocol\types\LevelEvent;

class LevelEventPacket extends DataPacket implements ClientboundPacket{
	public const NETWORK_ID = ProtocolInfo::LEVEL_EVENT_PACKET;

	/** @see LevelEvent */
	public int $eventId;
	public int $eventData;
	public ?Vector3 $position = null;

	/**
	 * @generate-create-func
	 */
	public static function create(int $eventId, int $eventData, ?Vector3 $position) : self{
		$result = new self;
		$result->eventId = $eventId;
		$result->eventData = $eventData;
		$result->position = $position;
		return $result;
	}

	public static function standardParticle(int $particleId, int $data, Vector3 $position) : self{
		return self::create(LevelEvent::ADD_PARTICLE_MASK | $particleId, $data, $position);
	}

	protected function decodePayload(PacketSerializer $in) : void{
		$this->eventId = $in->getVarInt();
		$this->position = $in->getVector3();
		$this->eventData = $in->getVarInt();
	}

	protected function encodePayload(PacketSerializer $out) : void{
		$out->putVarInt($this->eventId);
		$out->putVector3Nullable($this->position);
		$out->putVarInt($this->eventData);
	}

	public function handle(PacketHandlerInterface $handler) : bool{
		return $handler->handleLevelEvent($this);
	}
}
