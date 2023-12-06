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

	protected function decodePayload(PacketSerializer $in) : void{
		$x = $in->getLInt();
		$y = $in->getLInt();
		$z = $in->getLInt();
		$this->position = new BlockPosition($x, $y, $z);
		$this->slot = $in->getByte();
		$this->disabled = $in->getBool();
	}

	protected function encodePayload(PacketSerializer $out) : void{
		$out->putLInt($this->position->getX());
		$out->putLInt($this->position->getY());
		$out->putLInt($this->position->getZ());
		$out->putByte($this->slot);
		$out->putBool($this->disabled);
	}

	public function handle(PacketHandlerInterface $handler) : bool{
		return $handler->handlePlayerToggleCrafterSlotRequest($this);
	}
}
