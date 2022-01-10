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

class NetworkStackLatencyPacket extends DataPacket implements ClientboundPacket, ServerboundPacket{
	public const NETWORK_ID = ProtocolInfo::NETWORK_STACK_LATENCY_PACKET;

	public int $timestamp;
	public bool $needResponse;

	/**
	 * @generate-create-func
	 */
	public static function create(int $timestamp, bool $needResponse) : self{
		$result = new self;
		$result->timestamp = $timestamp;
		$result->needResponse = $needResponse;
		return $result;
	}

	public static function request(int $timestamp) : self{
		return self::create($timestamp, true);
	}

	public static function response(int $timestamp) : self{
		return self::create($timestamp, false);
	}

	protected function decodePayload(PacketSerializer $in) : void{
		$this->timestamp = $in->getLLong();
		$this->needResponse = $in->getBool();
	}

	protected function encodePayload(PacketSerializer $out) : void{
		$out->putLLong($this->timestamp);
		$out->putBool($this->needResponse);
	}

	public function handle(PacketHandlerInterface $handler) : bool{
		return $handler->handleNetworkStackLatency($this);
	}
}
