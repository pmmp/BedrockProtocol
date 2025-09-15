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

	protected function decodePayload(ByteBufferReader $in) : void{
		$this->difficulty = VarInt::readUnsignedInt($in);
	}

	protected function encodePayload(ByteBufferWriter $out) : void{
		VarInt::writeUnsignedInt($out, $this->difficulty);
	}

	public function handle(PacketHandlerInterface $handler) : bool{
		return $handler->handleSetDifficulty($this);
	}
}
