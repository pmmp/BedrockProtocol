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

class SubChunkRequestPacket extends DataPacket implements ServerboundPacket{
	public const NETWORK_ID = ProtocolInfo::SUB_CHUNK_REQUEST_PACKET;

	private int $dimension;
	private int $subChunkX;
	private int $subChunkY;
	private int $subChunkZ;

	/**
	 * @generate-create-func
	 */
	public static function create(int $dimension, int $subChunkX, int $subChunkY, int $subChunkZ) : self{
		$result = new self;
		$result->dimension = $dimension;
		$result->subChunkX = $subChunkX;
		$result->subChunkY = $subChunkY;
		$result->subChunkZ = $subChunkZ;
		return $result;
	}

	public function getDimension() : int{ return $this->dimension; }

	public function getSubChunkX() : int{ return $this->subChunkX; }

	public function getSubChunkY() : int{ return $this->subChunkY; }

	public function getSubChunkZ() : int{ return $this->subChunkZ; }

	protected function decodePayload(PacketSerializer $in) : void{
		$this->dimension = $in->getVarInt();
		$this->subChunkX = $in->getVarInt();
		$this->subChunkY = $in->getVarInt();
		$this->subChunkZ = $in->getVarInt();
	}

	protected function encodePayload(PacketSerializer $out) : void{
		$out->putVarInt($this->dimension);
		$out->putVarInt($this->subChunkX);
		$out->putVarInt($this->subChunkY);
		$out->putVarInt($this->subChunkZ);
	}

	public function handle(PacketHandlerInterface $handler) : bool{
		return $handler->handleSubChunkRequest($this);
	}
}
