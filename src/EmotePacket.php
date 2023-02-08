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

class EmotePacket extends DataPacket implements ClientboundPacket, ServerboundPacket{
	public const NETWORK_ID = ProtocolInfo::EMOTE_PACKET;

	public const FLAG_SERVER = 1 << 0;
	public const FLAG_MUTE_ANNOUNCEMENT = 1 << 1;

	private int $actorRuntimeId;
	private string $emoteId;
	private int $flags;

	/**
	 * @generate-create-func
	 */
	public static function create(int $actorRuntimeId, string $emoteId, int $flags) : self{
		$result = new self;
		$result->actorRuntimeId = $actorRuntimeId;
		$result->emoteId = $emoteId;
		$result->flags = $flags;
		return $result;
	}

	public function getActorRuntimeId() : int{
		return $this->actorRuntimeId;
	}

	public function getEmoteId() : string{
		return $this->emoteId;
	}

	public function getFlags() : int{
		return $this->flags;
	}

	protected function decodePayload(PacketSerializer $in) : void{
		$this->actorRuntimeId = $in->getActorRuntimeId();
		$this->emoteId = $in->getString();
		$this->flags = $in->getByte();
	}

	protected function encodePayload(PacketSerializer $out) : void{
		$out->putActorRuntimeId($this->actorRuntimeId);
		$out->putString($this->emoteId);
		$out->putByte($this->flags);
	}

	public function handle(PacketHandlerInterface $handler) : bool{
		return $handler->handleEmote($this);
	}
}
