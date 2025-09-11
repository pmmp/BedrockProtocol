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
use pocketmine\network\mcpe\protocol\types\PlayerAction;

class PlayerActionPacket extends DataPacket implements ClientboundPacket, ServerboundPacket{
	public const NETWORK_ID = ProtocolInfo::PLAYER_ACTION_PACKET;

	public int $actorRuntimeId;
	/** @see PlayerAction */
	public int $action;
	public BlockPosition $blockPosition;
	public BlockPosition $resultPosition;
	public int $face;

	/**
	 * @generate-create-func
	 */
	public static function create(int $actorRuntimeId, int $action, BlockPosition $blockPosition, BlockPosition $resultPosition, int $face) : self{
		$result = new self;
		$result->actorRuntimeId = $actorRuntimeId;
		$result->action = $action;
		$result->blockPosition = $blockPosition;
		$result->resultPosition = $resultPosition;
		$result->face = $face;
		return $result;
	}

	protected function decodePayload(ByteBufferReader $in) : void{
		$this->actorRuntimeId = CommonTypes::getActorRuntimeId($in);
		$this->action = VarInt::readSignedInt($in);
		$this->blockPosition = CommonTypes::getBlockPosition($in);
		$this->resultPosition = CommonTypes::getBlockPosition($in);
		$this->face = VarInt::readSignedInt($in);
	}

	protected function encodePayload(ByteBufferWriter $out) : void{
		CommonTypes::putActorRuntimeId($out, $this->actorRuntimeId);
		VarInt::writeSignedInt($out, $this->action);
		CommonTypes::putBlockPosition($out, $this->blockPosition);
		CommonTypes::putBlockPosition($out, $this->resultPosition);
		VarInt::writeSignedInt($out, $this->face);
	}

	public function handle(PacketHandlerInterface $handler) : bool{
		return $handler->handlePlayerAction($this);
	}
}
