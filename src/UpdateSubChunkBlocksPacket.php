<?php

/*
 *
 *  ____            _        _   __  __ _                  __  __ ____
 * |  _ \ ___   ___| | _____| |_|  \/  (_)_ __   ___      |  \/  |  _ \
 * | |_) / _ \ / __| |/ / _ \ __| |\/| | | '_ \ / _ \_____| |\/| | |_) |
 * |  __/ (_) | (__|   <  __/ |_| |  | | | | | |  __/_____| |  | |  __/
 * |_|   \___/ \___|_|\_\___|\__|_|  |_|_|_| |_|\___|     |_|  |_|_|
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * @author PocketMine Team
 * @link http://www.pocketmine.net/
 *
 *
 */

declare(strict_types=1);

namespace pocketmine\network\mcpe\protocol;

#include <rules/DataPacket.h>

use pocketmine\network\mcpe\protocol\serializer\PacketSerializer;
use pocketmine\network\mcpe\protocol\types\BlockPosition;
use pocketmine\network\mcpe\protocol\types\UpdateSubChunkBlocksPacketEntry;
use function count;

class UpdateSubChunkBlocksPacket extends DataPacket implements ClientboundPacket{
	public const NETWORK_ID = ProtocolInfo::UPDATE_SUB_CHUNK_BLOCKS_PACKET;

	private BlockPosition $baseBlockPosition;

	/** @var UpdateSubChunkBlocksPacketEntry[] */
	private array $layer0Updates;
	/** @var UpdateSubChunkBlocksPacketEntry[] */
	private array $layer1Updates;

	/**
	 * @generate-create-func
	 * @param UpdateSubChunkBlocksPacketEntry[] $layer0Updates
	 * @param UpdateSubChunkBlocksPacketEntry[] $layer1Updates
	 */
	public static function create(BlockPosition $baseBlockPosition, array $layer0Updates, array $layer1Updates) : self{
		$result = new self;
		$result->baseBlockPosition = $baseBlockPosition;
		$result->layer0Updates = $layer0Updates;
		$result->layer1Updates = $layer1Updates;
		return $result;
	}

	public function getBaseBlockPosition() : BlockPosition{ return $this->baseBlockPosition; }

	/** @return UpdateSubChunkBlocksPacketEntry[] */
	public function getLayer0Updates() : array{ return $this->layer0Updates; }

	/** @return UpdateSubChunkBlocksPacketEntry[] */
	public function getLayer1Updates() : array{ return $this->layer1Updates; }

	protected function decodePayload(PacketSerializer $in) : void{
		$this->baseBlockPosition = $in->getBlockPosition();
		$this->layer0Updates = [];
		for($i = 0, $count = $in->getUnsignedVarInt(); $i < $count; ++$i){
			$this->layer0Updates[] = UpdateSubChunkBlocksPacketEntry::read($in);
		}
		for($i = 0, $count = $in->getUnsignedVarInt(); $i < $count; ++$i){
			$this->layer1Updates[] = UpdateSubChunkBlocksPacketEntry::read($in);
		}
	}

	protected function encodePayload(PacketSerializer $out) : void{
		$out->putBlockPosition($this->baseBlockPosition);
		$out->putUnsignedVarInt(count($this->layer0Updates));
		foreach($this->layer0Updates as $update){
			$update->write($out);
		}
		$out->putUnsignedVarInt(count($this->layer1Updates));
		foreach($this->layer1Updates as $update){
			$update->write($out);
		}
	}

	public function handle(PacketHandlerInterface $handler) : bool{
		return $handler->handleUpdateSubChunkBlocks($this);
	}
}
