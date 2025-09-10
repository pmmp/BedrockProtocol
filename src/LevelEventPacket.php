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

use pmmp\encoding\ByteBufferReader;
use pmmp\encoding\ByteBufferWriter;
use pmmp\encoding\VarInt;
use pocketmine\math\Vector3;
use pocketmine\network\mcpe\protocol\serializer\CommonTypes;
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

	protected function decodePayload(ByteBufferReader $in) : void{
		$this->eventId = VarInt::readSignedInt($in);
		$this->position = CommonTypes::getVector3($in);
		$this->eventData = VarInt::readSignedInt($in);
	}

	protected function encodePayload(ByteBufferWriter $out) : void{
		VarInt::writeSignedInt($out, $this->eventId);
		CommonTypes::putVector3Nullable($out, $this->position);
		VarInt::writeSignedInt($out, $this->eventData);
	}

	public function handle(PacketHandlerInterface $handler) : bool{
		return $handler->handleLevelEvent($this);
	}
}
