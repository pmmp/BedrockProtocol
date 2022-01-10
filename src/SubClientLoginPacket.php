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

class SubClientLoginPacket extends DataPacket implements ServerboundPacket{
	public const NETWORK_ID = ProtocolInfo::SUB_CLIENT_LOGIN_PACKET;

	public string $connectionRequestData;

	/**
	 * @generate-create-func
	 */
	public static function create(string $connectionRequestData) : self{
		$result = new self;
		$result->connectionRequestData = $connectionRequestData;
		return $result;
	}

	protected function decodePayload(PacketSerializer $in) : void{
		$this->connectionRequestData = $in->getString();
	}

	protected function encodePayload(PacketSerializer $out) : void{
		$out->putString($this->connectionRequestData);
	}

	public function handle(PacketHandlerInterface $handler) : bool{
		return $handler->handleSubClientLogin($this);
	}
}
