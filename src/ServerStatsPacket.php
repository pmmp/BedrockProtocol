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

/**
 * Relays server performance statistics to the client.
 * It's currently unclear what the purpose of this packet is - probably to power some fancy debug screen.
 */
class ServerStatsPacket extends DataPacket implements ClientboundPacket{
	public const NETWORK_ID = ProtocolInfo::SERVER_STATS_PACKET;

	private float $serverTime;
	private float $networkTime;

	/**
	 * @generate-create-func
	 */
	public static function create(float $serverTime, float $networkTime) : self{
		$result = new self;
		$result->serverTime = $serverTime;
		$result->networkTime = $networkTime;
		return $result;
	}

	public function getServerTime() : float{ return $this->serverTime; }

	public function getNetworkTime() : float{ return $this->networkTime; }

	protected function decodePayload(ByteBufferReader $in) : void{
		$this->serverTime = LE::readFloat($in);
		$this->networkTime = LE::readFloat($in);
	}

	protected function encodePayload(ByteBufferWriter $out) : void{
		LE::writeFloat($out, $this->serverTime);
		LE::writeFloat($out, $this->networkTime);
	}

	public function handle(PacketHandlerInterface $handler) : bool{
		return $handler->handleServerStats($this);
	}
}
