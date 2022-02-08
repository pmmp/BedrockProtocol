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
use pocketmine\utils\Limits;
use function count;
use const PHP_INT_MAX;

class LevelChunkPacket extends DataPacket implements ClientboundPacket{
	public const NETWORK_ID = ProtocolInfo::LEVEL_CHUNK_PACKET;

	/**
	 * Client will request all subchunks as needed up to the top of the world
	 */
	private const CLIENT_REQUEST_FULL_COLUMN_FAKE_COUNT = Limits::UINT32_MAX;
	/**
	 * Client will request subchunks as needed up to the height written in the packet, and assume that anything above
	 * that height is air (wtf mojang ...)
	 */
	private const CLIENT_REQUEST_TRUNCATED_COLUMN_FAKE_COUNT = Limits::UINT32_MAX - 1;

	private int $chunkX;
	private int $chunkZ;
	private int $subChunkCount;
	private bool $clientSubChunkRequestsEnabled;
	/** @var int[]|null */
	private ?array $usedBlobHashes = null;
	private string $extraPayload;

	/**
	 * @generate-create-func
	 * @param int[] $usedBlobHashes
	 */
	public static function create(int $chunkX, int $chunkZ, int $subChunkCount, bool $clientSubChunkRequestsEnabled, ?array $usedBlobHashes, string $extraPayload) : self{
		$result = new self;
		$result->chunkX = $chunkX;
		$result->chunkZ = $chunkZ;
		$result->subChunkCount = $subChunkCount;
		$result->clientSubChunkRequestsEnabled = $clientSubChunkRequestsEnabled;
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

	public function isClientSubChunkRequestEnabled() : bool{
		return $this->clientSubChunkRequestsEnabled;
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

		$subChunkCountButNotReally = $in->getUnsignedVarInt();
		if($subChunkCountButNotReally === self::CLIENT_REQUEST_FULL_COLUMN_FAKE_COUNT){
			$this->clientSubChunkRequestsEnabled = true;
			$this->subChunkCount = PHP_INT_MAX;
		}elseif($subChunkCountButNotReally === self::CLIENT_REQUEST_TRUNCATED_COLUMN_FAKE_COUNT){
			$this->clientSubChunkRequestsEnabled = true;
			$this->subChunkCount = $in->getLShort();
		}else{
			$this->clientSubChunkRequestsEnabled = false;
			$this->subChunkCount = $subChunkCountButNotReally;
		}

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

		if($this->clientSubChunkRequestsEnabled){
			if($this->subChunkCount === PHP_INT_MAX){
				$out->putUnsignedVarInt(self::CLIENT_REQUEST_FULL_COLUMN_FAKE_COUNT);
			}else{
				$out->putUnsignedVarInt(self::CLIENT_REQUEST_TRUNCATED_COLUMN_FAKE_COUNT);
				$out->putLShort($this->subChunkCount);
			}
		}else{
			$out->putUnsignedVarInt($this->subChunkCount);
		}

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
