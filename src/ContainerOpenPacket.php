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

use pmmp\encoding\Byte;
use pmmp\encoding\ByteBufferReader;
use pmmp\encoding\ByteBufferWriter;
use pocketmine\network\mcpe\protocol\serializer\CommonTypes;
use pocketmine\network\mcpe\protocol\types\BlockPosition;

class ContainerOpenPacket extends DataPacket implements ClientboundPacket{
	public const NETWORK_ID = ProtocolInfo::CONTAINER_OPEN_PACKET;

	public int $windowId;
	public int $windowType;
	public BlockPosition $blockPosition;
	public int $actorUniqueId = -1;

	/**
	 * @generate-create-func
	 */
	private static function create(int $windowId, int $windowType, BlockPosition $blockPosition, int $actorUniqueId) : self{
		$result = new self;
		$result->windowId = $windowId;
		$result->windowType = $windowType;
		$result->blockPosition = $blockPosition;
		$result->actorUniqueId = $actorUniqueId;
		return $result;
	}

	public static function blockInv(int $windowId, int $windowType, BlockPosition $blockPosition) : self{
		return self::create($windowId, $windowType, $blockPosition, -1);
	}

	public static function entityInv(int $windowId, int $windowType, int $actorUniqueId) : self{
		return self::create($windowId, $windowType, new BlockPosition(0, 0, 0), $actorUniqueId);
	}

	protected function decodePayload(ByteBufferReader $in) : void{
		$this->windowId = Byte::readUnsigned($in);
		$this->windowType = Byte::readUnsigned($in);
		$this->blockPosition = CommonTypes::getBlockPosition($in);
		$this->actorUniqueId = CommonTypes::getActorUniqueId($in);
	}

	protected function encodePayload(ByteBufferWriter $out) : void{
		Byte::writeUnsigned($out, $this->windowId);
		Byte::writeUnsigned($out, $this->windowType);
		CommonTypes::putBlockPosition($out, $this->blockPosition);
		CommonTypes::putActorUniqueId($out, $this->actorUniqueId);
	}

	public function handle(PacketHandlerInterface $handler) : bool{
		return $handler->handleContainerOpen($this);
	}
}
