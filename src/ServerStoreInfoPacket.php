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
use pocketmine\network\mcpe\protocol\types\ClientStoreEntrypointConfig;

class ServerStoreInfoPacket extends DataPacket implements ClientboundPacket{
	public const NETWORK_ID = ProtocolInfo::SERVER_STORE_INFO_PACKET;

	private ?ClientStoreEntrypointConfig $clientStoreEntrypointConfig;

	/**
	 * @generate-create-func
	 */
	public static function create(?ClientStoreEntrypointConfig $clientStoreEntrypointConfig) : self{
		$result = new self;
		$result->clientStoreEntrypointConfig = $clientStoreEntrypointConfig;
		return $result;
	}

	public function getClientStoreEntrypointConfig() : ?ClientStoreEntrypointConfig{ return $this->clientStoreEntrypointConfig; }

	protected function decodePayload(ByteBufferReader $in) : void{
		$this->clientStoreEntrypointConfig = CommonTypes::readOptional($in, ClientStoreEntrypointConfig::read(...));
	}

	protected function encodePayload(ByteBufferWriter $out) : void{
		CommonTypes::writeOptional($out, $this->clientStoreEntrypointConfig, fn(ByteBufferWriter $out, ClientStoreEntrypointConfig $v) => $v->write($out));
	}

	public function handle(PacketHandlerInterface $handler) : bool{
		return $handler->handleServerStoreInfo($this);
	}
}
