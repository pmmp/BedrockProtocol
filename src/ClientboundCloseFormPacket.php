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

class ClientboundCloseFormPacket extends DataPacket implements ClientboundPacket{
	public const NETWORK_ID = ProtocolInfo::CLIENTBOUND_CLOSE_FORM_PACKET;

	/**
	 * @generate-create-func
	 */
	public static function create() : self{
		return new self;
	}

	protected function decodePayload(PacketSerializer $in) : void{
		//No payload
	}

	protected function encodePayload(PacketSerializer $out) : void{
		//No payload
	}

	public function handle(PacketHandlerInterface $handler) : bool{
		return $handler->handleClientboundCloseForm($this);
	}
}
