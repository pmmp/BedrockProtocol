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

class ResourcePackChunkRequestPacket extends DataPacket implements ServerboundPacket{
	public const NETWORK_ID = ProtocolInfo::RESOURCE_PACK_CHUNK_REQUEST_PACKET;

	public string $packId;
	public int $chunkIndex;

	/**
	 * @generate-create-func
	 */
	public static function create(string $packId, int $chunkIndex) : self{
		$result = new self;
		$result->packId = $packId;
		$result->chunkIndex = $chunkIndex;
		return $result;
	}

	protected function decodePayload(PacketSerializer $in) : void{
		$this->packId = $in->getString();
		$this->chunkIndex = $in->getLInt();
	}

	protected function encodePayload(PacketSerializer $out) : void{
		$out->putString($this->packId);
		$out->putLInt($this->chunkIndex);
	}

	public function handle(PacketHandlerInterface $handler) : bool{
		return $handler->handleResourcePackChunkRequest($this);
	}
}
