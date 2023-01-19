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

	protected function decodePayload(PacketSerializer $in) : void{
		$this->actorRuntimeId = $in->getActorRuntimeId();
		$this->action = $in->getVarInt();
		$this->blockPosition = $in->getBlockPosition();
		$this->resultPosition = $in->getBlockPosition();
		$this->face = $in->getVarInt();
	}

	protected function encodePayload(PacketSerializer $out) : void{
		$out->putActorRuntimeId($this->actorRuntimeId);
		$out->putVarInt($this->action);
		$out->putBlockPosition($this->blockPosition);
		$out->putBlockPosition($this->resultPosition);
		$out->putVarInt($this->face);
	}

	public function handle(PacketHandlerInterface $handler) : bool{
		return $handler->handlePlayerAction($this);
	}
}
