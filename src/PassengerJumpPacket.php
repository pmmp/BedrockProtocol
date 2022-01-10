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

class PassengerJumpPacket extends DataPacket implements ServerboundPacket{
	public const NETWORK_ID = ProtocolInfo::PASSENGER_JUMP_PACKET;

	public int $jumpStrength; //percentage

	/**
	 * @generate-create-func
	 */
	public static function create(int $jumpStrength) : self{
		$result = new self;
		$result->jumpStrength = $jumpStrength;
		return $result;
	}

	protected function decodePayload(PacketSerializer $in) : void{
		$this->jumpStrength = $in->getVarInt();
	}

	protected function encodePayload(PacketSerializer $out) : void{
		$out->putVarInt($this->jumpStrength);
	}

	public function handle(PacketHandlerInterface $handler) : bool{
		return $handler->handlePassengerJump($this);
	}
}
