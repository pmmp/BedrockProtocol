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

class DebugInfoPacket extends DataPacket implements ClientboundPacket, ServerboundPacket{
	public const NETWORK_ID = ProtocolInfo::DEBUG_INFO_PACKET;

	private int $actorUniqueId;
	private string $data;

	/**
	 * @generate-create-func
	 */
	public static function create(int $actorUniqueId, string $data) : self{
		$result = new self;
		$result->actorUniqueId = $actorUniqueId;
		$result->data = $data;
		return $result;
	}

	public function getActorUniqueId() : int{ return $this->actorUniqueId; }

	public function getData() : string{ return $this->data; }

	protected function decodePayload(PacketSerializer $in) : void{
		$this->actorUniqueId = $in->getActorUniqueId();
		$this->data = $in->getString();
	}

	protected function encodePayload(PacketSerializer $out) : void{
		$out->putActorUniqueId($this->actorUniqueId);
		$out->putString($this->data);
	}

	public function handle(PacketHandlerInterface $handler) : bool{
		return $handler->handleDebugInfo($this);
	}
}
