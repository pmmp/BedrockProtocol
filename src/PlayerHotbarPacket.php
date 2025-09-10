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
use pmmp\encoding\VarInt;
use pocketmine\network\mcpe\protocol\serializer\CommonTypes;
use pocketmine\network\mcpe\protocol\types\inventory\ContainerIds;

class PlayerHotbarPacket extends DataPacket implements ClientboundPacket, ServerboundPacket{
	public const NETWORK_ID = ProtocolInfo::PLAYER_HOTBAR_PACKET;

	public int $selectedHotbarSlot;
	public int $windowId = ContainerIds::INVENTORY;
	public bool $selectHotbarSlot = true;

	/**
	 * @generate-create-func
	 */
	public static function create(int $selectedHotbarSlot, int $windowId, bool $selectHotbarSlot) : self{
		$result = new self;
		$result->selectedHotbarSlot = $selectedHotbarSlot;
		$result->windowId = $windowId;
		$result->selectHotbarSlot = $selectHotbarSlot;
		return $result;
	}

	protected function decodePayload(ByteBufferReader $in) : void{
		$this->selectedHotbarSlot = VarInt::readUnsignedInt($in);
		$this->windowId = Byte::readUnsigned($in);
		$this->selectHotbarSlot = CommonTypes::getBool($in);
	}

	protected function encodePayload(ByteBufferWriter $out) : void{
		VarInt::writeUnsignedInt($out, $this->selectedHotbarSlot);
		Byte::writeUnsigned($out, $this->windowId);
		CommonTypes::putBool($out, $this->selectHotbarSlot);
	}

	public function handle(PacketHandlerInterface $handler) : bool{
		return $handler->handlePlayerHotbar($this);
	}
}
