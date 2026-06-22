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
use pocketmine\network\mcpe\protocol\serializer\CommonTypes;

class SendPartyDestinationCookiePacket extends DataPacket implements ClientboundPacket{
	public const NETWORK_ID = ProtocolInfo::SEND_PARTY_DESTINATION_COOKIE_PACKET;

	private string $cookie;
	private string $intent;
	private string $destinationName;

	/**
	 * @generate-create-func
	 */
	public static function create(string $cookie, string $intent, string $destinationName) : self{
		$result = new self;
		$result->cookie = $cookie;
		$result->intent = $intent;
		$result->destinationName = $destinationName;
		return $result;
	}

	public function getCookie() : string{ return $this->cookie; }

	public function getIntent() : string{ return $this->intent; }

	public function getDestinationName() : string{ return $this->destinationName; }

	protected function decodePayload(ByteBufferReader $in) : void{
		$this->cookie = CommonTypes::getString($in);
		$this->intent = CommonTypes::getString($in);
		$this->destinationName = CommonTypes::getString($in);
	}

	protected function encodePayload(ByteBufferWriter $out) : void{
		CommonTypes::putString($out, $this->cookie);
		CommonTypes::putString($out, $this->intent);
		CommonTypes::putString($out, $this->destinationName);
	}

	public function handle(PacketHandlerInterface $handler) : bool{
		return $handler->handleSendPartyDestinationCookie($this);
	}
}
