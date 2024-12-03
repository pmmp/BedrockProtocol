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

class UpdateSettingsPacket extends DataPacket implements ServerboundPacket{
	public const NETWORK_ID = ProtocolInfo::UPDATE_SETTINGS_PACKET;

	/** @var int */
	public int $defaultGameMode;
	/** @var int */
	public int $gameMode;
	/**
	* @generate-create-func
	*/
	public static function create(int $defaultGameMode, int $gameMode) : self{
		$packet = new self();
		$packet->defaultGameMode = $defaultGameMode;
		$packet->gameMode = $gameMode;
		return $packet;
	}

	protected function decodePayload(PacketSerializer $in) : void{
		$this->defaultGameMode = $in->getByte();
		$this->gameMode = $in->getByte();
	}

	protected function encodePayload(PacketSerializer $out) : void{
		$out->putByte($this->defaultGameMode);
		$out->putByte($this->gameMode);
	}

	public function handle(PacketHandlerInterface $handler) : bool{
		return $handler->handleUpdateSettingsPacket($this);
	}
}
