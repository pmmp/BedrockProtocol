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
use Ramsey\Uuid\UuidInterface;
use function count;

class EmoteListPacket extends DataPacket implements ClientboundPacket, ServerboundPacket{
	public const NETWORK_ID = ProtocolInfo::EMOTE_LIST_PACKET;

	private int $playerActorRuntimeId;
	/** @var UuidInterface[] */
	private array $emoteIds;

	/**
	 * @generate-create-func
	 * @param UuidInterface[] $emoteIds
	 */
	public static function create(int $playerActorRuntimeId, array $emoteIds) : self{
		$result = new self;
		$result->playerActorRuntimeId = $playerActorRuntimeId;
		$result->emoteIds = $emoteIds;
		return $result;
	}

	public function getPlayerActorRuntimeId() : int{ return $this->playerActorRuntimeId; }

	/** @return UuidInterface[] */
	public function getEmoteIds() : array{ return $this->emoteIds; }

	protected function decodePayload(PacketSerializer $in) : void{
		$this->playerActorRuntimeId = $in->getActorRuntimeId();
		$this->emoteIds = [];
		for($i = 0, $len = $in->getUnsignedVarInt(); $i < $len; ++$i){
			$this->emoteIds[] = $in->getUUID();
		}
	}

	protected function encodePayload(PacketSerializer $out) : void{
		$out->putActorRuntimeId($this->playerActorRuntimeId);
		$out->putUnsignedVarInt(count($this->emoteIds));
		foreach($this->emoteIds as $emoteId){
			$out->putUUID($emoteId);
		}
	}

	public function handle(PacketHandlerInterface $handler) : bool{
		return $handler->handleEmoteList($this);
	}
}
