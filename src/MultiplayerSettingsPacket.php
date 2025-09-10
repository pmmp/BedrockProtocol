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
use pmmp\encoding\VarInt;

class MultiplayerSettingsPacket extends DataPacket implements ClientboundPacket, ServerboundPacket{
	public const NETWORK_ID = ProtocolInfo::MULTIPLAYER_SETTINGS_PACKET;

	public const ACTION_ENABLE_MULTIPLAYER = 0;
	public const ACTION_DISABLE_MULTIPLAYER = 1;
	public const ACTION_REFRESH_JOIN_CODE = 2;

	private int $action;

	/**
	 * @generate-create-func
	 */
	public static function create(int $action) : self{
		$result = new self;
		$result->action = $action;
		return $result;
	}

	public function getAction() : int{
		return $this->action;
	}

	protected function decodePayload(ByteBufferReader $in) : void{
		$this->action = VarInt::readSignedInt($in);
	}

	protected function encodePayload(ByteBufferWriter $out) : void{
		VarInt::writeSignedInt($out, $this->action);
	}

	public function handle(PacketHandlerInterface $handler) : bool{
		return $handler->handleMultiplayerSettings($this);
	}
}
