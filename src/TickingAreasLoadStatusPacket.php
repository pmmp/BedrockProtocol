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

class TickingAreasLoadStatusPacket extends DataPacket implements ClientboundPacket{
	public const NETWORK_ID = ProtocolInfo::TICKING_AREAS_LOAD_STATUS_PACKET;

	private bool $waitingForPreload;

	/**
	 * @generate-create-func
	 */
	public static function create(bool $waitingForPreload) : self{
		$result = new self;
		$result->waitingForPreload = $waitingForPreload;
		return $result;
	}

	public function isWaitingForPreload() : bool{ return $this->waitingForPreload; }

	protected function decodePayload(ByteBufferReader $in) : void{
		$this->waitingForPreload = CommonTypes::getBool($in);
	}

	protected function encodePayload(ByteBufferWriter $out) : void{
		CommonTypes::putBool($out, $this->waitingForPreload);
	}

	public function handle(PacketHandlerInterface $handler) : bool{
		return $handler->handleTickingAreasLoadStatus($this);
	}
}
