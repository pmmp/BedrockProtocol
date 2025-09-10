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
use pocketmine\network\mcpe\protocol\serializer\CommonTypes;

class MobEffectPacket extends DataPacket implements ClientboundPacket{
	public const NETWORK_ID = ProtocolInfo::MOB_EFFECT_PACKET;

	public const EVENT_ADD = 1;
	public const EVENT_MODIFY = 2;
	public const EVENT_REMOVE = 3;

	public int $actorRuntimeId;
	public int $eventId;
	public int $effectId;
	public int $amplifier = 0;
	public bool $particles = true;
	public int $duration = 0;
	public int $tick = 0;

	/**
	 * @generate-create-func
	 */
	public static function create(
		int $actorRuntimeId,
		int $eventId,
		int $effectId,
		int $amplifier,
		bool $particles,
		int $duration,
		int $tick,
	) : self{
		$result = new self;
		$result->actorRuntimeId = $actorRuntimeId;
		$result->eventId = $eventId;
		$result->effectId = $effectId;
		$result->amplifier = $amplifier;
		$result->particles = $particles;
		$result->duration = $duration;
		$result->tick = $tick;
		return $result;
	}

	public static function add(int $actorRuntimeId, bool $replace, int $effectId, int $amplifier, bool $particles, int $duration, int $tick) : self{
		return self::create($actorRuntimeId, $replace ? self::EVENT_MODIFY : self::EVENT_ADD, $effectId, $amplifier, $particles, $duration, $tick);
	}

	public static function remove(int $actorRuntimeId, int $effectId, int $tick) : self{
		return self::create($actorRuntimeId, self::EVENT_REMOVE, $effectId, 0, false, 0, $tick);
	}

	protected function decodePayload(ByteBufferReader $in) : void{
		$this->actorRuntimeId = CommonTypes::getActorRuntimeId($in);
		$this->eventId = Byte::readUnsigned($in);
		$this->effectId = VarInt::readSignedInt($in);
		$this->amplifier = VarInt::readSignedInt($in);
		$this->particles = CommonTypes::getBool($in);
		$this->duration = VarInt::readSignedInt($in);
		$this->tick = VarInt::readUnsignedLong($in);
	}

	protected function encodePayload(ByteBufferWriter $out) : void{
		CommonTypes::putActorRuntimeId($out, $this->actorRuntimeId);
		Byte::writeUnsigned($out, $this->eventId);
		VarInt::writeSignedInt($out, $this->effectId);
		VarInt::writeSignedInt($out, $this->amplifier);
		CommonTypes::putBool($out, $this->particles);
		VarInt::writeSignedInt($out, $this->duration);
		VarInt::writeUnsignedLong($out, $this->tick);
	}

	public function handle(PacketHandlerInterface $handler) : bool{
		return $handler->handleMobEffect($this);
	}
}
