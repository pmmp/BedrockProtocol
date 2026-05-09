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
use pocketmine\network\mcpe\protocol\types\PresenceConfig;

class ServerPresenceInfoPacket extends DataPacket implements ClientboundPacket{
	public const NETWORK_ID = ProtocolInfo::SERVER_PRESENCE_INFO_PACKET;

	private ?PresenceConfig $presenceConfig;

	/**
	 * @generate-create-func
	 */
	public static function create(?PresenceConfig $presenceConfig) : self{
		$result = new self;
		$result->presenceConfig = $presenceConfig;
		return $result;
	}

	public function getPresenceConfig() : ?PresenceConfig{ return $this->presenceConfig; }

	protected function decodePayload(ByteBufferReader $in) : void{
		$this->presenceConfig = CommonTypes::readOptional($in, PresenceConfig::read(...));
	}

	protected function encodePayload(ByteBufferWriter $out) : void{
		CommonTypes::writeOptional($out, $this->presenceConfig, fn(ByteBufferWriter $out, PresenceConfig $v) => $v->write($out));
	}

	public function handle(PacketHandlerInterface $handler) : bool{
		return $handler->handleServerPresenceInfo($this);
	}
}
