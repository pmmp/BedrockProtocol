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
use pmmp\encoding\VarInt;
use pocketmine\network\mcpe\protocol\serializer\CommonTypes;

class EmotePacket extends DataPacket implements ClientboundPacket, ServerboundPacket{
	public const NETWORK_ID = ProtocolInfo::EMOTE_PACKET;

	public const FLAG_SERVER = 1 << 0;
	public const FLAG_MUTE_ANNOUNCEMENT = 1 << 1;

	private int $actorRuntimeId;
	private string $emoteId;
	private int $emoteLengthTicks;
	private string $xboxUserId;
	private string $platformChatId;
	private int $flags;

	/**
	 * @generate-create-func
	 */
	public static function create(int $actorRuntimeId, string $emoteId, int $emoteLengthTicks, string $xboxUserId, string $platformChatId, int $flags) : self{
		$result = new self;
		$result->actorRuntimeId = $actorRuntimeId;
		$result->emoteId = $emoteId;
		$result->emoteLengthTicks = $emoteLengthTicks;
		$result->xboxUserId = $xboxUserId;
		$result->platformChatId = $platformChatId;
		$result->flags = $flags;
		return $result;
	}

	public function getActorRuntimeId() : int{
		return $this->actorRuntimeId;
	}

	public function getEmoteId() : string{
		return $this->emoteId;
	}

	public function getEmoteLengthTicks() : int{ return $this->emoteLengthTicks; }

	public function getXboxUserId() : string{ return $this->xboxUserId; }

	public function getPlatformChatId() : string{ return $this->platformChatId; }

	public function getFlags() : int{
		return $this->flags;
	}

	protected function decodePayload(ByteBufferReader $in) : void{
		$this->actorRuntimeId = CommonTypes::getActorRuntimeId($in);
		$this->emoteId = CommonTypes::getString($in);
		$this->emoteLengthTicks = VarInt::readUnsignedInt($in);
		$this->xboxUserId = CommonTypes::getString($in);
		$this->platformChatId = CommonTypes::getString($in);
		$this->flags = Byte::readUnsigned($in);
	}

	protected function encodePayload(ByteBufferWriter $out) : void{
		CommonTypes::putActorRuntimeId($out, $this->actorRuntimeId);
		CommonTypes::putString($out, $this->emoteId);
		VarInt::writeUnsignedInt($out, $this->emoteLengthTicks);
		CommonTypes::putString($out, $this->xboxUserId);
		CommonTypes::putString($out, $this->platformChatId);
		Byte::writeUnsigned($out, $this->flags);
	}

	public function handle(PacketHandlerInterface $handler) : bool{
		return $handler->handleEmote($this);
	}
}
