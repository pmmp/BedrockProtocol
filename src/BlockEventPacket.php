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

class BlockEventPacket extends DataPacket implements ClientboundPacket{
	public const NETWORK_ID = ProtocolInfo::BLOCK_EVENT_PACKET;

	public BlockPosition $blockPosition;
	public int $eventType;
	public int $eventData;

	/**
	 * @generate-create-func
	 */
	public static function create(BlockPosition $blockPosition, int $eventType, int $eventData) : self{
		$result = new self;
		$result->blockPosition = $blockPosition;
		$result->eventType = $eventType;
		$result->eventData = $eventData;
		return $result;
	}

	protected function decodePayload(PacketSerializer $in) : void{
		$this->blockPosition = $in->getBlockPosition();
		$this->eventType = $in->getVarInt();
		$this->eventData = $in->getVarInt();
	}

	protected function encodePayload(PacketSerializer $out) : void{
		$out->putBlockPosition($this->blockPosition);
		$out->putVarInt($this->eventType);
		$out->putVarInt($this->eventData);
	}

	public function handle(PacketHandlerInterface $handler) : bool{
		return $handler->handleBlockEvent($this);
	}
}
