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
use pocketmine\network\mcpe\protocol\serializer\CommonTypes;
use pocketmine\network\mcpe\protocol\types\MovementEffectType;

class MovementEffectPacket extends DataPacket implements ClientboundPacket{
	public const NETWORK_ID = ProtocolInfo::MOVEMENT_EFFECT_PACKET;

	private int $actorRuntimeId;
	private MovementEffectType $effectType;
	private int $duration;
	private int $tick;

	/**
	 * @generate-create-func
	 */
	public static function create(int $actorRuntimeId, MovementEffectType $effectType, int $duration, int $tick) : self{
		$result = new self;
		$result->actorRuntimeId = $actorRuntimeId;
		$result->effectType = $effectType;
		$result->duration = $duration;
		$result->tick = $tick;
		return $result;
	}

	public function getActorRuntimeId() : int{ return $this->actorRuntimeId; }

	public function getEffectType() : MovementEffectType{ return $this->effectType; }

	public function getDuration() : int{ return $this->duration; }

	public function getTick() : int{ return $this->tick; }

	protected function decodePayload(ByteBufferReader $in) : void{
		$this->actorRuntimeId = CommonTypes::getActorRuntimeId($in);
		$this->effectType = MovementEffectType::fromPacket(VarInt::readUnsignedInt($in));
		$this->duration = VarInt::readUnsignedInt($in);
		$this->tick = VarInt::readUnsignedLong($in);
	}

	protected function encodePayload(ByteBufferWriter $out) : void{
		CommonTypes::putActorRuntimeId($out, $this->actorRuntimeId);
		VarInt::writeUnsignedInt($out, $this->effectType->value);
		VarInt::writeUnsignedInt($out, $this->duration);
		VarInt::writeUnsignedLong($out, $this->tick);
	}

	public function handle(PacketHandlerInterface $handler) : bool{
		return $handler->handleMovementEffect($this);
	}
}
