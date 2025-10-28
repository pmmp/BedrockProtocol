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
	public const ACTION_ROW_RIGHT = 128;
	public const ACTION_ROW_LEFT = 129;

	public int $action;
	public int $actorRuntimeId;
	public float $data = 0.0;
	public float $rowingTime = 0.0;

	public static function create(int $actorRuntimeId, int $actionId, float $data = 0.0) : self{
		$result = new self;
		$result->actorRuntimeId = $actorRuntimeId;
		$result->action = $actionId;
		$result->data = $data;
		return $result;
	}

	public static function boatHack(int $actorRuntimeId, int $actionId, float $rowingTime) : self{
		if($actionId !== self::ACTION_ROW_LEFT && $actionId !== self::ACTION_ROW_RIGHT){
			throw new \InvalidArgumentException("Invalid actionId for boatHack: $actionId");
		}

		$result = self::create($actorRuntimeId, $actionId);
		$result->rowingTime = $rowingTime;
		return $result;
	}

	protected function decodePayload(ByteBufferReader $in) : void{
		$this->action = VarInt::readSignedInt($in);
		$this->actorRuntimeId = CommonTypes::getActorRuntimeId($in);
		$this->data = LE::readFloat($in);
		if($this->action === self::ACTION_ROW_LEFT || $this->action === self::ACTION_ROW_RIGHT){
			$this->rowingTime = LE::readFloat($in);
		}
	}

	protected function encodePayload(ByteBufferWriter $out) : void{
		VarInt::writeSignedInt($out, $this->action);
		CommonTypes::putActorRuntimeId($out, $this->actorRuntimeId);
		LE::writeFloat($out, $this->data);
		if($this->action === self::ACTION_ROW_LEFT || $this->action === self::ACTION_ROW_RIGHT){
			LE::writeFloat($out, $this->rowingTime);
		}
	}

	public function handle(PacketHandlerInterface $handler) : bool{
		return $handler->handleAnimate($this);
	}
}
