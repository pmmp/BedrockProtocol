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

class SetPlayerGameTypePacket extends DataPacket implements ClientboundPacket, ServerboundPacket{
	public const NETWORK_ID = ProtocolInfo::SET_PLAYER_GAME_TYPE_PACKET;

	public int $gamemode;

	/**
	 * @generate-create-func
	 */
	public static function create(int $gamemode) : self{
		$result = new self;
		$result->gamemode = $gamemode;
		return $result;
	}

	protected function decodePayload(PacketSerializer $in) : void{
		$this->gamemode = $in->getVarInt();
	}

	protected function encodePayload(PacketSerializer $out) : void{
		$out->putVarInt($this->gamemode);
	}

	public function handle(PacketHandlerInterface $handler) : bool{
		return $handler->handleSetPlayerGameType($this);
	}
}
