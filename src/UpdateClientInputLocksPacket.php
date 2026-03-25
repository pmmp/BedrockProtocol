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

class UpdateClientInputLocksPacket extends DataPacket implements ClientboundPacket{
	public const NETWORK_ID = ProtocolInfo::UPDATE_CLIENT_INPUT_LOCKS_PACKET;

	private int $flags;

	/**
	 * @generate-create-func
	 */
	public static function create(int $flags) : self{
		$result = new self;
		$result->flags = $flags;
		return $result;
	}

	public function getFlags() : int{ return $this->flags; }

	protected function decodePayload(ByteBufferReader $in) : void{
		$this->flags = VarInt::readUnsignedInt($in);
	}

	protected function encodePayload(ByteBufferWriter $out) : void{
		VarInt::writeUnsignedInt($out, $this->flags);
	}

	public function handle(PacketHandlerInterface $handler) : bool{
		return $handler->handleUpdateClientInputLocks($this);
	}
}
