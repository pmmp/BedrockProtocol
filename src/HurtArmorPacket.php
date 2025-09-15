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

class HurtArmorPacket extends DataPacket implements ClientboundPacket{
	public const NETWORK_ID = ProtocolInfo::HURT_ARMOR_PACKET;

	public int $cause;
	public int $health;
	public int $armorSlotFlags;

	/**
	 * @generate-create-func
	 */
	public static function create(int $cause, int $health, int $armorSlotFlags) : self{
		$result = new self;
		$result->cause = $cause;
		$result->health = $health;
		$result->armorSlotFlags = $armorSlotFlags;
		return $result;
	}

	protected function decodePayload(ByteBufferReader $in) : void{
		$this->cause = VarInt::readSignedInt($in);
		$this->health = VarInt::readSignedInt($in);
		$this->armorSlotFlags = VarInt::readUnsignedLong($in);
	}

	protected function encodePayload(ByteBufferWriter $out) : void{
		VarInt::writeSignedInt($out, $this->cause);
		VarInt::writeSignedInt($out, $this->health);
		VarInt::writeUnsignedLong($out, $this->armorSlotFlags);
	}

	public function handle(PacketHandlerInterface $handler) : bool{
		return $handler->handleHurtArmor($this);
	}
}
