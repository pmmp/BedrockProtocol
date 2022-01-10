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

class ChunkRadiusUpdatedPacket extends DataPacket implements ClientboundPacket{
	public const NETWORK_ID = ProtocolInfo::CHUNK_RADIUS_UPDATED_PACKET;

	public int $radius;

	/**
	 * @generate-create-func
	 */
	public static function create(int $radius) : self{
		$result = new self;
		$result->radius = $radius;
		return $result;
	}

	protected function decodePayload(PacketSerializer $in) : void{
		$this->radius = $in->getVarInt();
	}

	protected function encodePayload(PacketSerializer $out) : void{
		$out->putVarInt($this->radius);
	}

	public function handle(PacketHandlerInterface $handler) : bool{
		return $handler->handleChunkRadiusUpdated($this);
	}
}
