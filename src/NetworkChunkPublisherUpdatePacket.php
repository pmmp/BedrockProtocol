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
use pocketmine\network\mcpe\protocol\types\BlockPosition;
use pocketmine\network\mcpe\protocol\types\ChunkPosition;
use function count;

class NetworkChunkPublisherUpdatePacket extends DataPacket implements ClientboundPacket{
	public const NETWORK_ID = ProtocolInfo::NETWORK_CHUNK_PUBLISHER_UPDATE_PACKET;

	public BlockPosition $blockPosition;
	public int $radius;
	/** @var ChunkPosition[] */
	public array $savedChunks = [];

	public const MAX_SAVED_CHUNKS = 9216;

	/**
	 * @generate-create-func
	 * @param ChunkPosition[] $savedChunks
	 */
	public static function create(BlockPosition $blockPosition, int $radius, array $savedChunks) : self{
		$result = new self;
		$result->blockPosition = $blockPosition;
		$result->radius = $radius;
		$result->savedChunks = $savedChunks;
		return $result;
	}

	protected function decodePayload(ByteBufferReader $in) : void{
		$this->blockPosition = CommonTypes::getSignedBlockPosition($in);
		$this->radius = VarInt::readUnsignedInt($in);

		$count = LE::readUnsignedInt($in);
		if($count > self::MAX_SAVED_CHUNKS){
			throw new PacketDecodeException("Expected at most " . self::MAX_SAVED_CHUNKS . " saved chunks, got " . $count);
		}
		for($i = 0, $this->savedChunks = []; $i < $count; $i++){
			$this->savedChunks[] = ChunkPosition::read($in);
		}
	}

	protected function encodePayload(ByteBufferWriter $out) : void{
		CommonTypes::putSignedBlockPosition($out, $this->blockPosition);
		VarInt::writeUnsignedInt($out, $this->radius);

		LE::writeUnsignedInt($out, count($this->savedChunks));
		foreach($this->savedChunks as $chunk){
			$chunk->write($out);
		}
	}

	public function handle(PacketHandlerInterface $handler) : bool{
		return $handler->handleNetworkChunkPublisherUpdate($this);
	}
}
