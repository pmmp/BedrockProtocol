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

class ScriptMessagePacket extends DataPacket implements ClientboundPacket, ServerboundPacket{
	public const NETWORK_ID = ProtocolInfo::SCRIPT_MESSAGE_PACKET;

	private string $messageId;
	private string $value;

	/**
	 * @generate-create-func
	 */
	public static function create(string $messageId, string $value) : self{
		$result = new self;
		$result->messageId = $messageId;
		$result->value = $value;
		return $result;
	}

	public function getMessageId() : string{ return $this->messageId; }

	public function getValue() : string{ return $this->value; }

	protected function decodePayload(PacketSerializer $in) : void{
		$this->messageId = $in->getString();
		$this->value = $in->getString();
	}

	protected function encodePayload(PacketSerializer $out) : void{
		$out->putString($this->messageId);
		$out->putString($this->value);
	}

	public function handle(PacketHandlerInterface $handler) : bool{
		return $handler->handleScriptMessage($this);
	}
}
