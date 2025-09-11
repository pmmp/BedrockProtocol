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
use pocketmine\network\mcpe\protocol\types\SubChunkPacketEntryWithCache as EntryWithBlobHash;
use pocketmine\network\mcpe\protocol\types\SubChunkPacketEntryWithCacheList as ListWithBlobHashes;
use pocketmine\network\mcpe\protocol\types\SubChunkPacketEntryWithoutCache as EntryWithoutBlobHash;
use pocketmine\network\mcpe\protocol\types\SubChunkPacketEntryWithoutCacheList as ListWithoutBlobHashes;
use pocketmine\network\mcpe\protocol\types\SubChunkPosition;
use function count;

class SubChunkPacket extends DataPacket implements ClientboundPacket{
	public const NETWORK_ID = ProtocolInfo::SUB_CHUNK_PACKET;

	private int $dimension;
	private SubChunkPosition $baseSubChunkPosition;
	private ListWithBlobHashes|ListWithoutBlobHashes $entries;

	/**
	 * @generate-create-func
	 */
	public static function create(int $dimension, SubChunkPosition $baseSubChunkPosition, ListWithBlobHashes|ListWithoutBlobHashes $entries) : self{
		$result = new self;
		$result->dimension = $dimension;
		$result->baseSubChunkPosition = $baseSubChunkPosition;
		$result->entries = $entries;
		return $result;
	}

	public function isCacheEnabled() : bool{ return $this->entries instanceof ListWithBlobHashes; }

	public function getDimension() : int{ return $this->dimension; }

	public function getBaseSubChunkPosition() : SubChunkPosition{ return $this->baseSubChunkPosition; }

	public function getEntries() : ListWithBlobHashes|ListWithoutBlobHashes{ return $this->entries; }

	protected function decodePayload(ByteBufferReader $in) : void{
		$cacheEnabled = CommonTypes::getBool($in);
		$this->dimension = VarInt::readSignedInt($in);
		$this->baseSubChunkPosition = SubChunkPosition::read($in);

		$count = LE::readUnsignedInt($in);
		if($cacheEnabled){
			$entries = [];
			for($i = 0; $i < $count; $i++){
				$entries[] = EntryWithBlobHash::read($in);
			}
			$this->entries = new ListWithBlobHashes($entries);
		}else{
			$entries = [];
			for($i = 0; $i < $count; $i++){
				$entries[] = EntryWithoutBlobHash::read($in);
			}
			$this->entries = new ListWithoutBlobHashes($entries);
		}
	}

	protected function encodePayload(ByteBufferWriter $out) : void{
		CommonTypes::putBool($out, $this->entries instanceof ListWithBlobHashes);
		VarInt::writeSignedInt($out, $this->dimension);
		$this->baseSubChunkPosition->write($out);

		LE::writeUnsignedInt($out, count($this->entries->getEntries()));

		foreach($this->entries->getEntries() as $entry){
			$entry->write($out);
		}
	}

	public function handle(PacketHandlerInterface $handler) : bool{
		return $handler->handleSubChunk($this);
	}
}
