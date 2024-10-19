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

use pocketmine\network\mcpe\protocol\serializer\PacketSerializer;
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

	protected function decodePayload(PacketSerializer $in) : void{
		$this->actorRuntimeId = $in->getActorRuntimeId();
		$this->effectType = MovementEffectType::fromPacket($in->getUnsignedVarInt());
		$this->duration = $in->getUnsignedVarInt();
		$this->tick = $in->getUnsignedVarLong();
	}

	protected function encodePayload(PacketSerializer $out) : void{
		$out->putActorRuntimeId($this->actorRuntimeId);
		$out->putUnsignedVarInt($this->effectType->value);
		$out->putUnsignedVarInt($this->duration);
		$out->putUnsignedVarLong($this->tick);
	}

	public function handle(PacketHandlerInterface $handler) : bool{
		return $handler->handleMovementEffect($this);
	}
}
