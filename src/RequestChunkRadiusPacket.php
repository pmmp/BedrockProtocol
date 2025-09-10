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

use pmmp\encoding\Byte;
use pmmp\encoding\ByteBufferReader;
use pmmp\encoding\ByteBufferWriter;
use pmmp\encoding\VarInt;

class RequestChunkRadiusPacket extends DataPacket implements ServerboundPacket{
	public const NETWORK_ID = ProtocolInfo::REQUEST_CHUNK_RADIUS_PACKET;

	public int $radius;
	public int $maxRadius;

	/**
	 * @generate-create-func
	 */
	public static function create(int $radius, int $maxRadius) : self{
		$result = new self;
		$result->radius = $radius;
		$result->maxRadius = $maxRadius;
		return $result;
	}

	protected function decodePayload(ByteBufferReader $in) : void{
		$this->radius = VarInt::readSignedInt($in);
		$this->maxRadius = Byte::readUnsigned($in);
	}

	protected function encodePayload(ByteBufferWriter $out) : void{
		VarInt::writeSignedInt($out, $this->radius);
		Byte::writeUnsigned($out, $this->maxRadius);
	}

	public function handle(PacketHandlerInterface $handler) : bool{
		return $handler->handleRequestChunkRadius($this);
	}
}
