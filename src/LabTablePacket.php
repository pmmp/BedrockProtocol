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

class LabTablePacket extends DataPacket implements ClientboundPacket, ServerboundPacket{
	public const NETWORK_ID = ProtocolInfo::LAB_TABLE_PACKET;

	public const TYPE_START_COMBINE = 0;
	public const TYPE_START_REACTION = 1;
	public const TYPE_RESET = 2;

	public int $actionType;
	public BlockPosition $blockPosition;
	public int $reactionType;

	/**
	 * @generate-create-func
	 */
	public static function create(int $actionType, BlockPosition $blockPosition, int $reactionType) : self{
		$result = new self;
		$result->actionType = $actionType;
		$result->blockPosition = $blockPosition;
		$result->reactionType = $reactionType;
		return $result;
	}

	protected function decodePayload(PacketSerializer $in) : void{
		$this->actionType = $in->getByte();
		$this->blockPosition = $in->getSignedBlockPosition();
		$this->reactionType = $in->getByte();
	}

	protected function encodePayload(PacketSerializer $out) : void{
		$out->putByte($this->actionType);
		$out->putSignedBlockPosition($this->blockPosition);
		$out->putByte($this->reactionType);
	}

	public function handle(PacketHandlerInterface $handler) : bool{
		return $handler->handleLabTable($this);
	}
}
