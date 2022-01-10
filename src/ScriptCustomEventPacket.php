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

class ScriptCustomEventPacket extends DataPacket{ //TODO: this doesn't have handlers in either client or server in the game as of 1.8
	public const NETWORK_ID = ProtocolInfo::SCRIPT_CUSTOM_EVENT_PACKET;

	public string $eventName;
	/** @var string json data */
	public string $eventData;

	/**
	 * @generate-create-func
	 */
	public static function create(string $eventName, string $eventData) : self{
		$result = new self;
		$result->eventName = $eventName;
		$result->eventData = $eventData;
		return $result;
	}

	protected function decodePayload(PacketSerializer $in) : void{
		$this->eventName = $in->getString();
		$this->eventData = $in->getString();
	}

	protected function encodePayload(PacketSerializer $out) : void{
		$out->putString($this->eventName);
		$out->putString($this->eventData);
	}

	public function handle(PacketHandlerInterface $handler) : bool{
		return $handler->handleScriptCustomEvent($this);
	}
}
