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
use pocketmine\network\mcpe\protocol\types\SubChunkPosition;
use pocketmine\network\mcpe\protocol\types\SubChunkPositionOffset;
use function count;

class SubChunkRequestPacket extends DataPacket implements ServerboundPacket{
	public const NETWORK_ID = ProtocolInfo::SUB_CHUNK_REQUEST_PACKET;

	private int $dimension;
	private SubChunkPosition $basePosition;
	/**
	 * @var SubChunkPositionOffset[]
	 * @phpstan-var list<SubChunkPositionOffset>
	 */
	private array $entries;

	/**
	 * @generate-create-func
	 * @param SubChunkPositionOffset[] $entries
	 * @phpstan-param list<SubChunkPositionOffset> $entries
	 */
	public static function create(int $dimension, SubChunkPosition $basePosition, array $entries) : self{
		$result = new self;
		$result->dimension = $dimension;
		$result->basePosition = $basePosition;
		$result->entries = $entries;
		return $result;
	}

	public function getDimension() : int{ return $this->dimension; }

	public function getBasePosition() : SubChunkPosition{ return $this->basePosition; }

	/**
	 * @return SubChunkPositionOffset[]
	 * @phpstan-return list<SubChunkPositionOffset>
	 */
	public function getEntries() : array{ return $this->entries; }

	protected function decodePayload(PacketSerializer $in) : void{
		$this->dimension = $in->getVarInt();
		$this->basePosition = SubChunkPosition::read($in);

		$this->entries = [];
		for($i = 0, $count = $in->getLInt(); $i < $count; $i++){
			$this->entries[] = SubChunkPositionOffset::read($in);
		}
	}

	protected function encodePayload(PacketSerializer $out) : void{
		$out->putVarInt($this->dimension);
		$this->basePosition->write($out);

		$out->putLInt(count($this->entries));
		foreach($this->entries as $entry){
			$entry->write($out);
		}
	}

	public function handle(PacketHandlerInterface $handler) : bool{
		return $handler->handleSubChunkRequest($this);
	}
}
