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
use pmmp\encoding\LE;
use pocketmine\network\mcpe\protocol\serializer\CommonTypes;
use pocketmine\network\mcpe\protocol\types\BlockPosition;

class PlayerToggleCrafterSlotRequestPacket extends DataPacket implements ServerboundPacket{
	public const NETWORK_ID = ProtocolInfo::PLAYER_TOGGLE_CRAFTER_SLOT_REQUEST_PACKET;

	private BlockPosition $position;
	private int $slot;
	private bool $disabled;

	/**
	 * @generate-create-func
	 */
	public static function create(BlockPosition $position, int $slot, bool $disabled) : self{
		$result = new self;
		$result->position = $position;
		$result->slot = $slot;
		$result->disabled = $disabled;
		return $result;
	}

	public function getPosition() : BlockPosition{ return $this->position; }

	public function getSlot() : int{ return $this->slot; }

	public function isDisabled() : bool{ return $this->disabled; }

	protected function decodePayload(ByteBufferReader $in) : void{
		$x = /* TODO: check if this should be unsigned */ LE::readSignedInt($in);
		$y = /* TODO: check if this should be unsigned */ LE::readSignedInt($in);
		$z = /* TODO: check if this should be unsigned */ LE::readSignedInt($in);
		$this->position = new BlockPosition($x, $y, $z);
		$this->slot = Byte::readUnsigned($in);
		$this->disabled = CommonTypes::getBool($in);
	}

	protected function encodePayload(ByteBufferWriter $out) : void{
		/* TODO: check if this should be unsigned */ LE::writeSignedInt($out, $this->position->getX());
		/* TODO: check if this should be unsigned */ LE::writeSignedInt($out, $this->position->getY());
		/* TODO: check if this should be unsigned */ LE::writeSignedInt($out, $this->position->getZ());
		Byte::writeUnsigned($out, $this->slot);
		CommonTypes::putBool($out, $this->disabled);
	}

	public function handle(PacketHandlerInterface $handler) : bool{
		return $handler->handlePlayerToggleCrafterSlotRequest($this);
	}
}
