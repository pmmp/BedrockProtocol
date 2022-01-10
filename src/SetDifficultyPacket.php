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

class SetDifficultyPacket extends DataPacket implements ClientboundPacket, ServerboundPacket{
	public const NETWORK_ID = ProtocolInfo::SET_DIFFICULTY_PACKET;

	public int $difficulty;

	/**
	 * @generate-create-func
	 */
	public static function create(int $difficulty) : self{
		$result = new self;
		$result->difficulty = $difficulty;
		return $result;
	}

	protected function decodePayload(PacketSerializer $in) : void{
		$this->difficulty = $in->getUnsignedVarInt();
	}

	protected function encodePayload(PacketSerializer $out) : void{
		$out->putUnsignedVarInt($this->difficulty);
	}

	public function handle(PacketHandlerInterface $handler) : bool{
		return $handler->handleSetDifficulty($this);
	}
}
