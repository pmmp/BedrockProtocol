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
use pocketmine\network\mcpe\protocol\serializer\CommonTypes;

class ContainerClosePacket extends DataPacket implements ClientboundPacket, ServerboundPacket{
	public const NETWORK_ID = ProtocolInfo::CONTAINER_CLOSE_PACKET;

	public int $windowId;
	public int $windowType;
	public bool $server = false;

	/**
	 * @generate-create-func
	 */
	public static function create(int $windowId, int $windowType, bool $server) : self{
		$result = new self;
		$result->windowId = $windowId;
		$result->windowType = $windowType;
		$result->server = $server;
		return $result;
	}

	protected function decodePayload(ByteBufferReader $in) : void{
		$this->windowId = Byte::readUnsigned($in);
		$this->windowType = Byte::readUnsigned($in);
		$this->server = CommonTypes::getBool($in);
	}

	protected function encodePayload(ByteBufferWriter $out) : void{
		Byte::writeUnsigned($out, $this->windowId);
		Byte::writeUnsigned($out, $this->windowType);
		CommonTypes::putBool($out, $this->server);
	}

	public function handle(PacketHandlerInterface $handler) : bool{
		return $handler->handleContainerClose($this);
	}
}
