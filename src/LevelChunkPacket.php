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
use function count;

class LevelChunkPacket extends DataPacket implements ClientboundPacket{
	public const NETWORK_ID = ProtocolInfo::LEVEL_CHUNK_PACKET;

	private int $chunkX;
	private int $chunkZ;
	private int $subChunkCount;
	/** @var int[]|null */
	private ?array $usedBlobHashes = null;
	private string $extraPayload;

	/**
	 * @generate-create-func
	 * @param int[] $usedBlobHashes
	 */
	public static function create(int $chunkX, int $chunkZ, int $subChunkCount, ?array $usedBlobHashes, string $extraPayload) : self{
		$result = new self;
		$result->chunkX = $chunkX;
		$result->chunkZ = $chunkZ;
		$result->subChunkCount = $subChunkCount;
		$result->usedBlobHashes = $usedBlobHashes;
		$result->extraPayload = $extraPayload;
		return $result;
	}

	public function getChunkX() : int{
		return $this->chunkX;
	}

	public function getChunkZ() : int{
		return $this->chunkZ;
	}

	public function getSubChunkCount() : int{
		return $this->subChunkCount;
	}

	public function isCacheEnabled() : bool{
		return $this->usedBlobHashes !== null;
	}

	/**
	 * @return int[]|null
	 */
	public function getUsedBlobHashes() : ?array{
		return $this->usedBlobHashes;
	}

	public function getExtraPayload() : string{
		return $this->extraPayload;
	}

	protected function decodePayload(PacketSerializer $in) : void{
		$this->chunkX = $in->getVarInt();
		$this->chunkZ = $in->getVarInt();
		$this->subChunkCount = $in->getUnsignedVarInt();
		$cacheEnabled = $in->getBool();
		if($cacheEnabled){
			$this->usedBlobHashes = [];
			for($i = 0, $count = $in->getUnsignedVarInt(); $i < $count; ++$i){
				$this->usedBlobHashes[] = $in->getLLong();
			}
		}
		$this->extraPayload = $in->getString();
	}

	protected function encodePayload(PacketSerializer $out) : void{
		$out->putVarInt($this->chunkX);
		$out->putVarInt($this->chunkZ);
		$out->putUnsignedVarInt($this->subChunkCount);
		$out->putBool($this->usedBlobHashes !== null);
		if($this->usedBlobHashes !== null){
			$out->putUnsignedVarInt(count($this->usedBlobHashes));
			foreach($this->usedBlobHashes as $hash){
				$out->putLLong($hash);
			}
		}
		$out->putString($this->extraPayload);
	}

	public function handle(PacketHandlerInterface $handler) : bool{
		return $handler->handleLevelChunk($this);
	}
}
