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

class StopSoundPacket extends DataPacket implements ClientboundPacket{
	public const NETWORK_ID = ProtocolInfo::STOP_SOUND_PACKET;

	public string $soundName;
	public bool $stopAll;

	/**
	 * @generate-create-func
	 */
	public static function create(string $soundName, bool $stopAll) : self{
		$result = new self;
		$result->soundName = $soundName;
		$result->stopAll = $stopAll;
		return $result;
	}

	protected function decodePayload(PacketSerializer $in) : void{
		$this->soundName = $in->getString();
		$this->stopAll = $in->getBool();
	}

	protected function encodePayload(PacketSerializer $out) : void{
		$out->putString($this->soundName);
		$out->putBool($this->stopAll);
	}

	public function handle(PacketHandlerInterface $handler) : bool{
		return $handler->handleStopSound($this);
	}
}
