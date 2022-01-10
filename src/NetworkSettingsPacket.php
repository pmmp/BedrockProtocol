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

class NetworkSettingsPacket extends DataPacket implements ClientboundPacket{
	public const NETWORK_ID = ProtocolInfo::NETWORK_SETTINGS_PACKET;

	public const COMPRESS_NOTHING = 0;
	public const COMPRESS_EVERYTHING = 1;

	private int $compressionThreshold;

	/**
	 * @generate-create-func
	 */
	public static function create(int $compressionThreshold) : self{
		$result = new self;
		$result->compressionThreshold = $compressionThreshold;
		return $result;
	}

	public function getCompressionThreshold() : int{
		return $this->compressionThreshold;
	}

	protected function decodePayload(PacketSerializer $in) : void{
		$this->compressionThreshold = $in->getLShort();
	}

	protected function encodePayload(PacketSerializer $out) : void{
		$out->putLShort($this->compressionThreshold);
	}

	public function handle(PacketHandlerInterface $handler) : bool{
		return $handler->handleNetworkSettings($this);
	}
}
