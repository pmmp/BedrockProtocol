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
use pocketmine\network\mcpe\protocol\types\ServerAuthMovementMode;

class SetMovementAuthorityPacket extends DataPacket implements ClientboundPacket{
	public const NETWORK_ID = ProtocolInfo::SET_MOVEMENT_AUTHORITY_PACKET;

	private ServerAuthMovementMode $mode;

	/**
	 * @generate-create-func
	 */
	public static function create(ServerAuthMovementMode $mode) : self{
		$result = new self;
		$result->mode = $mode;
		return $result;
	}

	public function getMode() : ServerAuthMovementMode{ return $this->mode; }

	protected function decodePayload(PacketSerializer $in) : void{
		$this->mode = ServerAuthMovementMode::fromPacket($in->getByte());
	}

	protected function encodePayload(PacketSerializer $out) : void{
		$out->putByte($this->mode->value);
	}

	public function handle(PacketHandlerInterface $handler) : bool{
		return $handler->handleSetMovementAuthority($this);
	}
}
