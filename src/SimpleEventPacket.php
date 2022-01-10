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

class SimpleEventPacket extends DataPacket implements ClientboundPacket, ServerboundPacket{
	public const NETWORK_ID = ProtocolInfo::SIMPLE_EVENT_PACKET;

	public const TYPE_ENABLE_COMMANDS = 1;
	public const TYPE_DISABLE_COMMANDS = 2;
	public const TYPE_UNLOCK_WORLD_TEMPLATE_SETTINGS = 3;

	public int $eventType;

	/**
	 * @generate-create-func
	 */
	public static function create(int $eventType) : self{
		$result = new self;
		$result->eventType = $eventType;
		return $result;
	}

	protected function decodePayload(PacketSerializer $in) : void{
		$this->eventType = $in->getLShort();
	}

	protected function encodePayload(PacketSerializer $out) : void{
		$out->putLShort($this->eventType);
	}

	public function handle(PacketHandlerInterface $handler) : bool{
		return $handler->handleSimpleEvent($this);
	}
}
