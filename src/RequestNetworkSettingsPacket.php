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

/**
 * This is the first packet sent in a game session. It contains the client's protocol version.
 * The server is expected to respond to this with network settings, which will instruct the client which compression
 * type to use, amongst other things.
 */
class RequestNetworkSettingsPacket extends DataPacket implements ServerboundPacket{
	public const NETWORK_ID = ProtocolInfo::REQUEST_NETWORK_SETTINGS_PACKET;

	private int $protocolVersion;

	/**
	 * @generate-create-func
	 */
	public static function create(int $protocolVersion) : self{
		$result = new self;
		$result->protocolVersion = $protocolVersion;
		return $result;
	}

	public function canBeSentBeforeLogin() : bool{
		return true;
	}

	public function getProtocolVersion() : int{ return $this->protocolVersion; }

	protected function decodePayload(PacketSerializer $in) : void{
		$this->protocolVersion = $in->getInt();
	}

	protected function encodePayload(PacketSerializer $out) : void{
		$out->putInt($this->protocolVersion);
	}

	public function handle(PacketHandlerInterface $handler) : bool{
		return $handler->handleRequestNetworkSettings($this);
	}
}
