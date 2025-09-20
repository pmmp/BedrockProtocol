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

class StopSoundPacket extends DataPacket implements ClientboundPacket{
	public const NETWORK_ID = ProtocolInfo::STOP_SOUND_PACKET;

	public string $soundName;
	public bool $stopAll;
	public bool $stopLegacyMusic;

	/**
	 * @generate-create-func
	 */
	public static function create(string $soundName, bool $stopAll, bool $stopLegacyMusic) : self{
		$result = new self;
		$result->soundName = $soundName;
		$result->stopAll = $stopAll;
		$result->stopLegacyMusic = $stopLegacyMusic;
		return $result;
	}

	protected function decodePayload(ByteBufferReader $in) : void{
		$this->soundName = CommonTypes::getString($in);
		$this->stopAll = CommonTypes::getBool($in);
		$this->stopLegacyMusic = CommonTypes::getBool($in);
	}

	protected function encodePayload(ByteBufferWriter $out) : void{
		CommonTypes::putString($out, $this->soundName);
		CommonTypes::putBool($out, $this->stopAll);
		CommonTypes::putBool($out, $this->stopLegacyMusic);
	}

	public function handle(PacketHandlerInterface $handler) : bool{
		return $handler->handleStopSound($this);
	}
}
