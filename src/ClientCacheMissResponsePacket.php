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
use pocketmine\network\mcpe\protocol\serializer\CommonTypes;
use pocketmine\network\mcpe\protocol\types\ChunkCacheBlob;
use function count;

class ClientCacheMissResponsePacket extends DataPacket implements ClientboundPacket{
	public const NETWORK_ID = ProtocolInfo::CLIENT_CACHE_MISS_RESPONSE_PACKET;

	/** @var ChunkCacheBlob[] */
	private array $blobs = [];

	/**
	 * @generate-create-func
	 * @param ChunkCacheBlob[] $blobs
	 */
	public static function create(array $blobs) : self{
		$result = new self;
		$result->blobs = $blobs;
		return $result;
	}

	/**
	 * @return ChunkCacheBlob[]
	 */
	public function getBlobs() : array{
		return $this->blobs;
	}

	protected function decodePayload(ByteBufferReader $in) : void{
		for($i = 0, $count = VarInt::readUnsignedInt($in); $i < $count; ++$i){
			$hash = LE::readUnsignedLong($in);
			$payload = CommonTypes::getString($in);
			$this->blobs[] = new ChunkCacheBlob($hash, $payload);
		}
	}

	protected function encodePayload(ByteBufferWriter $out) : void{
		VarInt::writeUnsignedInt($out, count($this->blobs));
		foreach($this->blobs as $blob){
			LE::writeUnsignedLong($out, $blob->getHash());
			CommonTypes::putString($out, $blob->getPayload());
		}
	}

	public function handle(PacketHandlerInterface $handler) : bool{
		return $handler->handleClientCacheMissResponse($this);
	}
}
