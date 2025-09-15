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
use pmmp\encoding\LE;
use pocketmine\network\mcpe\protocol\serializer\CommonTypes;

class InteractPacket extends DataPacket implements ServerboundPacket{
	public const NETWORK_ID = ProtocolInfo::INTERACT_PACKET;

	public const ACTION_LEAVE_VEHICLE = 3;
	public const ACTION_MOUSEOVER = 4;
	public const ACTION_OPEN_NPC = 5;
	public const ACTION_OPEN_INVENTORY = 6;

	public int $action;
	public int $targetActorRuntimeId;
	public float $x;
	public float $y;
	public float $z;

	protected function decodePayload(ByteBufferReader $in) : void{
		$this->action = Byte::readUnsigned($in);
		$this->targetActorRuntimeId = CommonTypes::getActorRuntimeId($in);

		if($this->action === self::ACTION_MOUSEOVER || $this->action === self::ACTION_LEAVE_VEHICLE){
			//TODO: should this be a vector3?
			$this->x = LE::readFloat($in);
			$this->y = LE::readFloat($in);
			$this->z = LE::readFloat($in);
		}
	}

	protected function encodePayload(ByteBufferWriter $out) : void{
		Byte::writeUnsigned($out, $this->action);
		CommonTypes::putActorRuntimeId($out, $this->targetActorRuntimeId);

		if($this->action === self::ACTION_MOUSEOVER || $this->action === self::ACTION_LEAVE_VEHICLE){
			LE::writeFloat($out, $this->x);
			LE::writeFloat($out, $this->y);
			LE::writeFloat($out, $this->z);
		}
	}

	public function handle(PacketHandlerInterface $handler) : bool{
		return $handler->handleInteract($this);
	}
}
