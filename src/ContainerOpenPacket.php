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

	protected function decodePayload(PacketSerializer $in) : void{
		$this->windowId = $in->getByte();
		$this->windowType = $in->getByte();
		$this->blockPosition = $in->getBlockPosition();
		$this->actorUniqueId = $in->getActorUniqueId();
	}

	protected function encodePayload(PacketSerializer $out) : void{
		$out->putByte($this->windowId);
		$out->putByte($this->windowType);
		$out->putBlockPosition($this->blockPosition);
		$out->putActorUniqueId($this->actorUniqueId);
	}

	public function handle(PacketHandlerInterface $handler) : bool{
		return $handler->handleContainerOpen($this);
	}
}
