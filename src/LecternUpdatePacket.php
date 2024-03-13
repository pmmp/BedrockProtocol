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

class LecternUpdatePacket extends DataPacket implements ServerboundPacket{
	public const NETWORK_ID = ProtocolInfo::LECTERN_UPDATE_PACKET;

	public int $page;
	public int $totalPages;
	public BlockPosition $blockPosition;

	/**
	 * @generate-create-func
	 */
	public static function create(int $page, int $totalPages, BlockPosition $blockPosition) : self{
		$result = new self;
		$result->page = $page;
		$result->totalPages = $totalPages;
		$result->blockPosition = $blockPosition;
		return $result;
	}

	protected function decodePayload(PacketSerializer $in) : void{
		$this->page = $in->getByte();
		$this->totalPages = $in->getByte();
		$this->blockPosition = $in->getBlockPosition();
	}

	protected function encodePayload(PacketSerializer $out) : void{
		$out->putByte($this->page);
		$out->putByte($this->totalPages);
		$out->putBlockPosition($this->blockPosition);
	}

	public function handle(PacketHandlerInterface $handler) : bool{
		return $handler->handleLecternUpdate($this);
	}
}
