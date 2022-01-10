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

class SetHealthPacket extends DataPacket implements ClientboundPacket{
	public const NETWORK_ID = ProtocolInfo::SET_HEALTH_PACKET;

	public int $health;

	/**
	 * @generate-create-func
	 */
	public static function create(int $health) : self{
		$result = new self;
		$result->health = $health;
		return $result;
	}

	protected function decodePayload(PacketSerializer $in) : void{
		$this->health = $in->getVarInt();
	}

	protected function encodePayload(PacketSerializer $out) : void{
		$out->putVarInt($this->health);
	}

	public function handle(PacketHandlerInterface $handler) : bool{
		return $handler->handleSetHealth($this);
	}
}
