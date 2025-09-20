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

	protected function decodePayload(ByteBufferReader $in) : void{
		$this->actorUniqueId = LE::readSignedLong($in);
		$this->hotbarSlot = Byte::readUnsigned($in);
		$this->addUserData = CommonTypes::getBool($in);
	}

	protected function encodePayload(ByteBufferWriter $out) : void{
		LE::writeSignedLong($out, $this->actorUniqueId);
		Byte::writeUnsigned($out, $this->hotbarSlot);
		CommonTypes::putBool($out, $this->addUserData);
	}

	public function handle(PacketHandlerInterface $handler) : bool{
		return $handler->handleActorPickRequest($this);
	}
}
