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

namespace pocketmine\network\mcpe\protocol\types;

use pmmp\encoding\ByteBufferReader;
use pmmp\encoding\ByteBufferWriter;
use pmmp\encoding\VarInt;
use pocketmine\network\mcpe\protocol\serializer\CommonTypes;
use pocketmine\network\mcpe\protocol\UpdateBlockPacket;

final class UpdateSubChunkBlocksPacketEntry{
	public function __construct(
		private BlockPosition $blockPosition,
		private int $blockRuntimeId,
		private int $flags,
		//These two fields are useless 99.9% of the time; they are here to allow this packet to provide UpdateBlockSyncedPacket functionality.
		private int $syncedUpdateActorUniqueId,
		private int $syncedUpdateType
	){}

	public static function simple(BlockPosition $blockPosition, int $blockRuntimeId) : self{
		return new self($blockPosition, $blockRuntimeId, UpdateBlockPacket::FLAG_NETWORK, 0, 0);
	}

	public function getBlockPosition() : BlockPosition{ return $this->blockPosition; }

	public function getBlockRuntimeId() : int{ return $this->blockRuntimeId; }

	public function getFlags() : int{ return $this->flags; }

	public function getSyncedUpdateActorUniqueId() : int{ return $this->syncedUpdateActorUniqueId; }

	public function getSyncedUpdateType() : int{ return $this->syncedUpdateType; }

	public static function read(ByteBufferReader $in) : self{
		$blockPosition = CommonTypes::getBlockPosition($in);
		$blockRuntimeId = VarInt::readUnsignedInt($in);
		$updateFlags = VarInt::readUnsignedInt($in);
		$syncedUpdateActorUniqueId = VarInt::readUnsignedLong($in); //this can't use the standard method because it's unsigned as opposed to the usual signed... !!!!!!
		$syncedUpdateType = VarInt::readUnsignedInt($in); //this isn't even consistent with UpdateBlockSyncedPacket?!

		return new self($blockPosition, $blockRuntimeId, $updateFlags, $syncedUpdateActorUniqueId, $syncedUpdateType);
	}

	public function write(ByteBufferWriter $out) : void{
		CommonTypes::putBlockPosition($out, $this->blockPosition);
		VarInt::writeUnsignedInt($out, $this->blockRuntimeId);
		VarInt::writeUnsignedInt($out, $this->flags);
		VarInt::writeUnsignedLong($out, $this->syncedUpdateActorUniqueId);
		VarInt::writeUnsignedInt($out, $this->syncedUpdateType);
	}
}
