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

class ServerboundDataDrivenScreenClosedPacket extends DataPacket implements ServerboundPacket{
	public const NETWORK_ID = ProtocolInfo::SERVERBOUND_DATA_DRIVEN_SCREEN_CLOSED_PACKET;

	private ?int $formId;
	private int $closeReason;

	/**
	 * @generate-create-func
	 */
	public static function create(?int $formId, int $closeReason) : self{
		$result = new self;
		$result->formId = $formId;
		$result->closeReason = $closeReason;
		return $result;
	}

	public function getFormId() : ?int{ return $this->formId; }

	public function getCloseReason() : int{ return $this->closeReason; }

	protected function decodePayload(ByteBufferReader $in) : void{
		$this->formId = CommonTypes::readOptional($in, LE::readUnsignedInt(...));
		$this->closeReason = Byte::readUnsigned($in);
	}

	protected function encodePayload(ByteBufferWriter $out) : void{
		CommonTypes::writeOptional($out, $this->formId, LE::writeUnsignedInt(...));
		Byte::writeUnsigned($out, $this->closeReason);
	}

	public function handle(PacketHandlerInterface $handler) : bool{
		return $handler->handleServerboundDataDrivenScreenClosed($this);
	}
}
