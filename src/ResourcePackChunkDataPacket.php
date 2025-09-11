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
use pmmp\encoding\LE;
use pocketmine\network\mcpe\protocol\serializer\CommonTypes;

class ResourcePackChunkDataPacket extends DataPacket implements ClientboundPacket{
	public const NETWORK_ID = ProtocolInfo::RESOURCE_PACK_CHUNK_DATA_PACKET;

	public string $packId;
	public int $chunkIndex;
	public int $offset;
	public string $data;

	/**
	 * @generate-create-func
	 */
	public static function create(string $packId, int $chunkIndex, int $offset, string $data) : self{
		$result = new self;
		$result->packId = $packId;
		$result->chunkIndex = $chunkIndex;
		$result->offset = $offset;
		$result->data = $data;
		return $result;
	}

	protected function decodePayload(ByteBufferReader $in) : void{
		$this->packId = CommonTypes::getString($in);
		$this->chunkIndex = LE::readUnsignedInt($in);
		$this->offset = LE::readUnsignedLong($in);
		$this->data = CommonTypes::getString($in);
	}

	protected function encodePayload(ByteBufferWriter $out) : void{
		CommonTypes::putString($out, $this->packId);
		LE::writeUnsignedInt($out, $this->chunkIndex);
		LE::writeUnsignedLong($out, $this->offset);
		CommonTypes::putString($out, $this->data);
	}

	public function handle(PacketHandlerInterface $handler) : bool{
		return $handler->handleResourcePackChunkData($this);
	}
}
