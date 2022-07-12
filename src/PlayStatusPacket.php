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

class PlayStatusPacket extends DataPacket implements ClientboundPacket{
	public const NETWORK_ID = ProtocolInfo::PLAY_STATUS_PACKET;

	public const LOGIN_SUCCESS = 0;
	public const LOGIN_FAILED_CLIENT = 1;
	public const LOGIN_FAILED_SERVER = 2;
	public const PLAYER_SPAWN = 3;
	public const LOGIN_FAILED_INVALID_TENANT = 4;
	public const LOGIN_FAILED_VANILLA_EDU = 5;
	public const LOGIN_FAILED_EDU_VANILLA = 6;
	public const LOGIN_FAILED_SERVER_FULL = 7;
	public const LOGIN_FAILED_EDITOR_VANILLA = 8;
	public const LOGIN_FAILED_VANILLA_EDITOR = 9;

	public int $status;

	/**
	 * @generate-create-func
	 */
	public static function create(int $status) : self{
		$result = new self;
		$result->status = $status;
		return $result;
	}

	protected function decodePayload(PacketSerializer $in) : void{
		$this->status = $in->getInt();
	}

	public function canBeSentBeforeLogin() : bool{
		return true;
	}

	protected function encodePayload(PacketSerializer $out) : void{
		$out->putInt($this->status);
	}

	public function handle(PacketHandlerInterface $handler) : bool{
		return $handler->handlePlayStatus($this);
	}
}
