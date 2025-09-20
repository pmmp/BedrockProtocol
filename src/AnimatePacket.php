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
use pmmp\encoding\LE;
use pmmp\encoding\VarInt;
use pocketmine\network\mcpe\protocol\serializer\CommonTypes;

class AnimatePacket extends DataPacket implements ClientboundPacket, ServerboundPacket{
	public const NETWORK_ID = ProtocolInfo::ANIMATE_PACKET;

	public const ACTION_SWING_ARM = 1;

	public const ACTION_STOP_SLEEP = 3;
	public const ACTION_CRITICAL_HIT = 4;
	public const ACTION_MAGICAL_CRITICAL_HIT = 5;

	public int $action;
	public int $actorRuntimeId;
	public float $float = 0.0; //TODO (Boat rowing time?)

	public static function create(int $actorRuntimeId, int $actionId) : self{
		$result = new self;
		$result->actorRuntimeId = $actorRuntimeId;
		$result->action = $actionId;
		return $result;
	}

	public static function boatHack(int $actorRuntimeId, int $actionId, float $data) : self{
		$result = self::create($actorRuntimeId, $actionId);
		$result->float = $data;
		return $result;
	}

	protected function decodePayload(ByteBufferReader $in) : void{
		$this->action = VarInt::readSignedInt($in);
		$this->actorRuntimeId = CommonTypes::getActorRuntimeId($in);
		if(($this->action & 0x80) !== 0){
			$this->float = LE::readFloat($in);
		}
	}

	protected function encodePayload(ByteBufferWriter $out) : void{
		VarInt::writeSignedInt($out, $this->action);
		CommonTypes::putActorRuntimeId($out, $this->actorRuntimeId);
		if(($this->action & 0x80) !== 0){
			LE::writeFloat($out, $this->float);
		}
	}

	public function handle(PacketHandlerInterface $handler) : bool{
		return $handler->handleAnimate($this);
	}
}
