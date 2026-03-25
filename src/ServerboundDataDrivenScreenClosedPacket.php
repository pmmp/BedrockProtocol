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
use pmmp\encoding\LE;
use pocketmine\network\mcpe\protocol\serializer\CommonTypes;

class ServerboundDataDrivenScreenClosedPacket extends DataPacket{
	public const NETWORK_ID = ProtocolInfo::SERVERBOUND_DATA_DRIVEN_SCREEN_CLOSED_PACKET;

	private int $formId;
	private string $closeReason;

	/**
	 * @generate-create-func
	 */
	public static function create(int $formId, string $closeReason) : self{
		$result = new self;
		$result->formId = $formId;
		$result->closeReason = $closeReason;
		return $result;
	}

	protected function decodePayload(ByteBufferReader $in) : void{
		$this->formId = LE::readUnsignedInt($in);
		$this->closeReason = CommonTypes::getString($in);
	}

	protected function encodePayload(ByteBufferWriter $out) : void{
		LE::writeUnsignedInt($out, $this->formId);
		CommonTypes::putString($out, $this->closeReason);
	}

	public function handle(PacketHandlerInterface $handler) : bool{
		return $handler->handleServerboundDataDrivenScreenClosed($this);
	}
}
