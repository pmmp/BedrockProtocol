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
use pocketmine\network\mcpe\protocol\serializer\CommonTypes;

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

	protected function decodePayload(ByteBufferReader $in) : void{
		$this->messageId = CommonTypes::getString($in);
		$this->value = CommonTypes::getString($in);
	}

	protected function encodePayload(ByteBufferWriter $out) : void{
		CommonTypes::putString($out, $this->messageId);
		CommonTypes::putString($out, $this->value);
	}

	public function handle(PacketHandlerInterface $handler) : bool{
		return $handler->handleScriptMessage($this);
	}
}
