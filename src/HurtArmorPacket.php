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

	protected function decodePayload(PacketSerializer $in) : void{
		$this->cause = $in->getVarInt();
		$this->health = $in->getVarInt();
		$this->armorSlotFlags = $in->getUnsignedVarLong();
	}

	protected function encodePayload(PacketSerializer $out) : void{
		$out->putVarInt($this->cause);
		$out->putVarInt($this->health);
		$out->putUnsignedVarLong($this->armorSlotFlags);
	}

	public function handle(PacketHandlerInterface $handler) : bool{
		return $handler->handleHurtArmor($this);
	}
}
