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
use pocketmine\network\mcpe\protocol\serializer\CommonTypes;

class PacketViolationWarningPacket extends DataPacket implements ServerboundPacket{
	public const NETWORK_ID = ProtocolInfo::PACKET_VIOLATION_WARNING_PACKET;

	public const TYPE_MALFORMED = 0;

	public const SEVERITY_WARNING = 0;
	public const SEVERITY_FINAL_WARNING = 1;
	public const SEVERITY_TERMINATING_CONNECTION = 2;

	private int $type;
	private int $severity;
	private int $packetId;
	private string $message;

	/**
	 * @generate-create-func
	 */
	public static function create(int $type, int $severity, int $packetId, string $message) : self{
		$result = new self;
		$result->type = $type;
		$result->severity = $severity;
		$result->packetId = $packetId;
		$result->message = $message;
		return $result;
	}

	public function getType() : int{ return $this->type; }

	public function getSeverity() : int{ return $this->severity; }

	public function getPacketId() : int{ return $this->packetId; }

	public function getMessage() : string{ return $this->message; }

	protected function decodePayload(ByteBufferReader $in) : void{
		$this->type = VarInt::readSignedInt($in);
		$this->severity = VarInt::readSignedInt($in);
		$this->packetId = VarInt::readSignedInt($in);
		$this->message = CommonTypes::getString($in);
	}

	protected function encodePayload(ByteBufferWriter $out) : void{
		VarInt::writeSignedInt($out, $this->type);
		VarInt::writeSignedInt($out, $this->severity);
		VarInt::writeSignedInt($out, $this->packetId);
		CommonTypes::putString($out, $this->message);
	}

	public function handle(PacketHandlerInterface $handler) : bool{
		return $handler->handlePacketViolationWarning($this);
	}
}
