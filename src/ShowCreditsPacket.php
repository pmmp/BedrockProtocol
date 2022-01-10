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

class ShowCreditsPacket extends DataPacket implements ClientboundPacket, ServerboundPacket{
	public const NETWORK_ID = ProtocolInfo::SHOW_CREDITS_PACKET;

	public const STATUS_START_CREDITS = 0;
	public const STATUS_END_CREDITS = 1;

	public int $playerActorRuntimeId;
	public int $status;

	/**
	 * @generate-create-func
	 */
	public static function create(int $playerActorRuntimeId, int $status) : self{
		$result = new self;
		$result->playerActorRuntimeId = $playerActorRuntimeId;
		$result->status = $status;
		return $result;
	}

	protected function decodePayload(PacketSerializer $in) : void{
		$this->playerActorRuntimeId = $in->getActorRuntimeId();
		$this->status = $in->getVarInt();
	}

	protected function encodePayload(PacketSerializer $out) : void{
		$out->putActorRuntimeId($this->playerActorRuntimeId);
		$out->putVarInt($this->status);
	}

	public function handle(PacketHandlerInterface $handler) : bool{
		return $handler->handleShowCredits($this);
	}
}
