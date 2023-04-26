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

/**
 * Sent by the server to open the sign GUI for a sign.
 */
class OpenSignPacket extends DataPacket implements ClientboundPacket{
	public const NETWORK_ID = ProtocolInfo::OPEN_SIGN_PACKET;

	private BlockPosition $blockPosition;
	private bool $front;

	/**
	 * @generate-create-func
	 */
	public static function create(BlockPosition $blockPosition, bool $front) : self{
		$result = new self;
		$result->blockPosition = $blockPosition;
		$result->front = $front;
		return $result;
	}

	public function getBlockPosition() : BlockPosition{ return $this->blockPosition; }

	public function isFront() : bool{ return $this->front; }

	protected function decodePayload(PacketSerializer $in) : void{
		$this->blockPosition = $in->getBlockPosition();
		$this->front = $in->getBool();
	}

	protected function encodePayload(PacketSerializer $out) : void{
		$out->putBlockPosition($this->blockPosition);
		$out->putBool($this->front);
	}

	public function handle(PacketHandlerInterface $handler) : bool{
		return $handler->handleOpenSign($this);
	}
}
