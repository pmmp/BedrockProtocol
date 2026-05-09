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
use pmmp\encoding\VarInt;
use pocketmine\math\Vector3;
use pocketmine\network\mcpe\protocol\serializer\CommonTypes;
use pocketmine\network\mcpe\protocol\types\ActorEvent;

class ActorEventPacket extends DataPacket implements ClientboundPacket, ServerboundPacket{
	public const NETWORK_ID = ProtocolInfo::ACTOR_EVENT_PACKET;

	public int $actorRuntimeId;
	/** @see ActorEvent */
	public int $eventId;
	public int $eventData = 0;
	public ?Vector3 $firePosition = null;

	/**
	 * @generate-create-func
	 */
	public static function create(int $actorRuntimeId, int $eventId, int $eventData, ?Vector3 $firePosition) : self{
		$result = new self;
		$result->actorRuntimeId = $actorRuntimeId;
		$result->eventId = $eventId;
		$result->eventData = $eventData;
		$result->firePosition = $firePosition;
		return $result;
	}

	protected function decodePayload(ByteBufferReader $in) : void{
		$this->actorRuntimeId = CommonTypes::getActorRuntimeId($in);
		$this->eventId = Byte::readUnsigned($in);
		$this->eventData = VarInt::readSignedInt($in);
		$this->firePosition = CommonTypes::readOptional($in, CommonTypes::getVector3(...));
	}

	protected function encodePayload(ByteBufferWriter $out) : void{
		CommonTypes::putActorRuntimeId($out, $this->actorRuntimeId);
		Byte::writeUnsigned($out, $this->eventId);
		VarInt::writeSignedInt($out, $this->eventData);
		CommonTypes::writeOptional($out, $this->firePosition, CommonTypes::putVector3(...));
	}

	public function handle(PacketHandlerInterface $handler) : bool{
		return $handler->handleActorEvent($this);
	}
}
