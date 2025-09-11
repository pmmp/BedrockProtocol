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
use pocketmine\network\mcpe\protocol\serializer\CommonTypes;
use pocketmine\network\mcpe\protocol\types\GameMode;

class UpdatePlayerGameTypePacket extends DataPacket implements ClientboundPacket{
	public const NETWORK_ID = ProtocolInfo::UPDATE_PLAYER_GAME_TYPE_PACKET;

	/** @see GameMode */
	private int $gameMode;
	private int $playerActorUniqueId;
	private int $tick;

	/**
	 * @generate-create-func
	 */
	public static function create(int $gameMode, int $playerActorUniqueId, int $tick) : self{
		$result = new self;
		$result->gameMode = $gameMode;
		$result->playerActorUniqueId = $playerActorUniqueId;
		$result->tick = $tick;
		return $result;
	}

	public function getGameMode() : int{ return $this->gameMode; }

	public function getPlayerActorUniqueId() : int{ return $this->playerActorUniqueId; }

	public function getTick() : int{ return $this->tick; }

	protected function decodePayload(ByteBufferReader $in) : void{
		$this->gameMode = VarInt::readSignedInt($in);
		$this->playerActorUniqueId = CommonTypes::getActorUniqueId($in);
		$this->tick = VarInt::readUnsignedLong($in);
	}

	protected function encodePayload(ByteBufferWriter $out) : void{
		VarInt::writeSignedInt($out, $this->gameMode);
		CommonTypes::putActorUniqueId($out, $this->playerActorUniqueId);
		VarInt::writeUnsignedLong($out, $this->tick);
	}

	public function handle(PacketHandlerInterface $handler) : bool{
		return $handler->handleUpdatePlayerGameType($this);
	}
}
