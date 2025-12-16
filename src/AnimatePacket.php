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

class AnimatePacket extends DataPacket implements ClientboundPacket, ServerboundPacket{
	public const NETWORK_ID = ProtocolInfo::ANIMATE_PACKET;

	public const ACTION_SWING_ARM = 1;

	public const ACTION_STOP_SLEEP = 3;
	public const ACTION_CRITICAL_HIT = 4;
	public const ACTION_MAGICAL_CRITICAL_HIT = 5;

	public int $action;
	public int $actorRuntimeId;
	public float $data = 0.0;
	public ?string $swingSource = null;

	public static function create(int $actorRuntimeId, int $action, float $data = 0.0, ?string $swingSource = null) : self{
		$result = new self;
		$result->actorRuntimeId = $actorRuntimeId;
		$result->action = $action;
		$result->data = $data;
		$result->swingSource = $swingSource;
		return $result;
	}

	protected function decodePayload(ByteBufferReader $in) : void{
		$this->action = Byte::readUnsigned($in);
		$this->actorRuntimeId = CommonTypes::getActorRuntimeId($in);
		$this->data = LE::readFloat($in);
		$this->swingSource = CommonTypes::readOptional($in, CommonTypes::getString(...));
	}

	protected function encodePayload(ByteBufferWriter $out) : void{
		Byte::writeUnsigned($out, $this->action);
		CommonTypes::putActorRuntimeId($out, $this->actorRuntimeId);
		LE::writeFloat($out, $this->data);
		CommonTypes::writeOptional($out, $this->swingSource, CommonTypes::putString(...));
	}

	public function handle(PacketHandlerInterface $handler) : bool{
		return $handler->handleAnimate($this);
	}
}
