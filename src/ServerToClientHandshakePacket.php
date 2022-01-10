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

class ServerToClientHandshakePacket extends DataPacket implements ClientboundPacket{
	public const NETWORK_ID = ProtocolInfo::SERVER_TO_CLIENT_HANDSHAKE_PACKET;

	/** Server pubkey and token is contained in the JWT. */
	public string $jwt;

	/**
	 * @generate-create-func
	 */
	public static function create(string $jwt) : self{
		$result = new self;
		$result->jwt = $jwt;
		return $result;
	}

	public function canBeSentBeforeLogin() : bool{
		return true;
	}

	protected function decodePayload(PacketSerializer $in) : void{
		$this->jwt = $in->getString();
	}

	protected function encodePayload(PacketSerializer $out) : void{
		$out->putString($this->jwt);
	}

	public function handle(PacketHandlerInterface $handler) : bool{
		return $handler->handleServerToClientHandshake($this);
	}
}
