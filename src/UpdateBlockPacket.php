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
use pmmp\encoding\VarInt;
use pocketmine\network\mcpe\protocol\serializer\CommonTypes;
use pocketmine\network\mcpe\protocol\types\BlockPosition;

class UpdateBlockPacket extends DataPacket implements ClientboundPacket{
	public const NETWORK_ID = ProtocolInfo::UPDATE_BLOCK_PACKET;

	public const FLAG_NONE = 0b0000;
	public const FLAG_NEIGHBORS = 0b0001;
	public const FLAG_NETWORK = 0b0010;
	public const FLAG_NOGRAPHIC = 0b0100;
	public const FLAG_PRIORITY = 0b1000;

	public const DATA_LAYER_NORMAL = 0;
	public const DATA_LAYER_LIQUID = 1;

	public BlockPosition $blockPosition;
	public int $blockRuntimeId;
	/**
	 * @var int
	 * Flags are used by MCPE internally for block setting, but only flag 2 (network flag) is relevant for network.
	 * This field is pointless really.
	 */
	public int $flags = self::FLAG_NETWORK;
	public int $dataLayerId = self::DATA_LAYER_NORMAL;

	/**
	 * @generate-create-func
	 */
	public static function create(BlockPosition $blockPosition, int $blockRuntimeId, int $flags, int $dataLayerId) : self{
		$result = new self;
		$result->blockPosition = $blockPosition;
		$result->blockRuntimeId = $blockRuntimeId;
		$result->flags = $flags;
		$result->dataLayerId = $dataLayerId;
		return $result;
	}

	protected function decodePayload(ByteBufferReader $in) : void{
		$this->blockPosition = CommonTypes::getBlockPosition($in);
		$this->blockRuntimeId = VarInt::readUnsignedInt($in);
		$this->flags = VarInt::readUnsignedInt($in);
		$this->dataLayerId = VarInt::readUnsignedInt($in);
	}

	protected function encodePayload(ByteBufferWriter $out) : void{
		CommonTypes::putBlockPosition($out, $this->blockPosition);
		VarInt::writeUnsignedInt($out, $this->blockRuntimeId);
		VarInt::writeUnsignedInt($out, $this->flags);
		VarInt::writeUnsignedInt($out, $this->dataLayerId);
	}

	public function handle(PacketHandlerInterface $handler) : bool{
		return $handler->handleUpdateBlock($this);
	}
}
