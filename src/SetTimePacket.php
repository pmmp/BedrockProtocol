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

class SetTimePacket extends DataPacket implements ClientboundPacket{
	public const NETWORK_ID = ProtocolInfo::SET_TIME_PACKET;

	public int $time;

	public static function create(int $time) : self{
		$result = new self;
		$result->time = $time & 0xffffffff; //avoid overflowing the field, since the packet uses an int32
		return $result;
	}

	protected function decodePayload(PacketSerializer $in) : void{
		$this->time = $in->getVarInt();
	}

	protected function encodePayload(PacketSerializer $out) : void{
		$out->putVarInt($this->time);
	}

	public function handle(PacketHandlerInterface $handler) : bool{
		return $handler->handleSetTime($this);
	}
}
