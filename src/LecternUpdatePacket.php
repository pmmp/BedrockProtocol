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

	protected function decodePayload(ByteBufferReader $in) : void{
		$this->page = Byte::readUnsigned($in);
		$this->totalPages = Byte::readUnsigned($in);
		$this->blockPosition = CommonTypes::getBlockPosition($in);
	}

	protected function encodePayload(ByteBufferWriter $out) : void{
		Byte::writeUnsigned($out, $this->page);
		Byte::writeUnsigned($out, $this->totalPages);
		CommonTypes::putBlockPosition($out, $this->blockPosition);
	}

	public function handle(PacketHandlerInterface $handler) : bool{
		return $handler->handleLecternUpdate($this);
	}
}
