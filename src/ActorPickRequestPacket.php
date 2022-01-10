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

class ActorPickRequestPacket extends DataPacket implements ServerboundPacket{
	public const NETWORK_ID = ProtocolInfo::ACTOR_PICK_REQUEST_PACKET;

	public int $actorUniqueId;
	public int $hotbarSlot;
	public bool $addUserData;

	/**
	 * @generate-create-func
	 */
	public static function create(int $actorUniqueId, int $hotbarSlot, bool $addUserData) : self{
		$result = new self;
		$result->actorUniqueId = $actorUniqueId;
		$result->hotbarSlot = $hotbarSlot;
		$result->addUserData = $addUserData;
		return $result;
	}

	protected function decodePayload(PacketSerializer $in) : void{
		$this->actorUniqueId = $in->getLLong();
		$this->hotbarSlot = $in->getByte();
		$this->addUserData = $in->getBool();
	}

	protected function encodePayload(PacketSerializer $out) : void{
		$out->putLLong($this->actorUniqueId);
		$out->putByte($this->hotbarSlot);
		$out->putBool($this->addUserData);
	}

	public function handle(PacketHandlerInterface $handler) : bool{
		return $handler->handleActorPickRequest($this);
	}
}
