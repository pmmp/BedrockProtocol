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
use pocketmine\network\mcpe\protocol\serializer\CommonTypes;
use pocketmine\network\mcpe\protocol\types\BlockPosition;
use pocketmine\network\mcpe\protocol\types\StructureEditorData;

class StructureBlockUpdatePacket extends DataPacket implements ServerboundPacket{
	public const NETWORK_ID = ProtocolInfo::STRUCTURE_BLOCK_UPDATE_PACKET;

	public BlockPosition $blockPosition;
	public StructureEditorData $structureEditorData;
	public bool $isPowered;
	public bool $waterlogged;

	/**
	 * @generate-create-func
	 */
	public static function create(BlockPosition $blockPosition, StructureEditorData $structureEditorData, bool $isPowered, bool $waterlogged) : self{
		$result = new self;
		$result->blockPosition = $blockPosition;
		$result->structureEditorData = $structureEditorData;
		$result->isPowered = $isPowered;
		$result->waterlogged = $waterlogged;
		return $result;
	}

	protected function decodePayload(ByteBufferReader $in) : void{
		$this->blockPosition = CommonTypes::getBlockPosition($in);
		$this->structureEditorData = CommonTypes::getStructureEditorData($in);
		$this->isPowered = CommonTypes::getBool($in);
		$this->waterlogged = CommonTypes::getBool($in);
	}

	protected function encodePayload(ByteBufferWriter $out) : void{
		CommonTypes::putBlockPosition($out, $this->blockPosition);
		CommonTypes::putStructureEditorData($out, $this->structureEditorData);
		CommonTypes::putBool($out, $this->isPowered);
		CommonTypes::putBool($out, $this->waterlogged);
	}

	public function handle(PacketHandlerInterface $handler) : bool{
		return $handler->handleStructureBlockUpdate($this);
	}
}
