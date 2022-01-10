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

class PlayerInputPacket extends DataPacket implements ServerboundPacket{
	public const NETWORK_ID = ProtocolInfo::PLAYER_INPUT_PACKET;

	public float $motionX;
	public float $motionY;
	public bool $jumping;
	public bool $sneaking;

	/**
	 * @generate-create-func
	 */
	public static function create(float $motionX, float $motionY, bool $jumping, bool $sneaking) : self{
		$result = new self;
		$result->motionX = $motionX;
		$result->motionY = $motionY;
		$result->jumping = $jumping;
		$result->sneaking = $sneaking;
		return $result;
	}

	protected function decodePayload(PacketSerializer $in) : void{
		$this->motionX = $in->getLFloat();
		$this->motionY = $in->getLFloat();
		$this->jumping = $in->getBool();
		$this->sneaking = $in->getBool();
	}

	protected function encodePayload(PacketSerializer $out) : void{
		$out->putLFloat($this->motionX);
		$out->putLFloat($this->motionY);
		$out->putBool($this->jumping);
		$out->putBool($this->sneaking);
	}

	public function handle(PacketHandlerInterface $handler) : bool{
		return $handler->handlePlayerInput($this);
	}
}
