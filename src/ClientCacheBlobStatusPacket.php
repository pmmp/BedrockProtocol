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
use pmmp\encoding\VarInt;
use function count;

class ClientCacheBlobStatusPacket extends DataPacket implements ServerboundPacket{
	public const NETWORK_ID = ProtocolInfo::CLIENT_CACHE_BLOB_STATUS_PACKET;

	/** @var int[] xxHash64 subchunk data hashes */
	private array $hitHashes = [];
	/** @var int[] xxHash64 subchunk data hashes */
	private array $missHashes = [];

	/**
	 * @generate-create-func
	 * @param int[] $hitHashes
	 * @param int[] $missHashes
	 */
	public static function create(array $hitHashes, array $missHashes) : self{
		$result = new self;
		$result->hitHashes = $hitHashes;
		$result->missHashes = $missHashes;
		return $result;
	}

	/**
	 * @return int[]
	 */
	public function getHitHashes() : array{
		return $this->hitHashes;
	}

	/**
	 * @return int[]
	 */
	public function getMissHashes() : array{
		return $this->missHashes;
	}

	protected function decodePayload(ByteBufferReader $in) : void{
		$missCount = VarInt::readUnsignedInt($in);
		$hitCount = VarInt::readUnsignedInt($in);
		for($i = 0; $i < $missCount; ++$i){
			$this->missHashes[] = LE::readUnsignedLong($in);
		}
		for($i = 0; $i < $hitCount; ++$i){
			$this->hitHashes[] = LE::readUnsignedLong($in);
		}
	}

	protected function encodePayload(ByteBufferWriter $out) : void{
		VarInt::writeUnsignedInt($out, count($this->missHashes));
		VarInt::writeUnsignedInt($out, count($this->hitHashes));
		foreach($this->missHashes as $hash){
			LE::writeUnsignedLong($out, $hash);
		}
		foreach($this->hitHashes as $hash){
			LE::writeUnsignedLong($out, $hash);
		}
	}

	public function handle(PacketHandlerInterface $handler) : bool{
		return $handler->handleClientCacheBlobStatus($this);
	}
}
