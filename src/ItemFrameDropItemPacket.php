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

class ItemFrameDropItemPacket extends DataPacket implements ServerboundPacket{

	public const NETWORK_ID = ProtocolInfo::ITEM_FRAME_DROP_ITEM_PACKET;

	public BlockPosition $blockPosition;

	/**
	 * @generate-create-func
	 */
	public static function create(BlockPosition $blockPosition) : self{
		$result = new self;
		$result->blockPosition = $blockPosition;
		return $result;
	}

	protected function decodePayload(PacketSerializer $in) : void{
		$this->blockPosition = $in->getBlockPosition();
	}

	protected function encodePayload(PacketSerializer $out) : void{
		$out->putBlockPosition($this->blockPosition);
	}

	public function handle(PacketHandlerInterface $handler) : bool{
		return $handler->handleItemFrameDropItem($this);
	}
}
