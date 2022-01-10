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
use pocketmine\network\mcpe\protocol\types\ScoreboardIdentityPacketEntry;
use function count;

class SetScoreboardIdentityPacket extends DataPacket implements ClientboundPacket{
	public const NETWORK_ID = ProtocolInfo::SET_SCOREBOARD_IDENTITY_PACKET;

	public const TYPE_REGISTER_IDENTITY = 0;
	public const TYPE_CLEAR_IDENTITY = 1;

	public int $type;
	/** @var ScoreboardIdentityPacketEntry[] */
	public array $entries = [];

	/**
	 * @generate-create-func
	 * @param ScoreboardIdentityPacketEntry[] $entries
	 */
	public static function create(int $type, array $entries) : self{
		$result = new self;
		$result->type = $type;
		$result->entries = $entries;
		return $result;
	}

	protected function decodePayload(PacketSerializer $in) : void{
		$this->type = $in->getByte();
		for($i = 0, $count = $in->getUnsignedVarInt(); $i < $count; ++$i){
			$entry = new ScoreboardIdentityPacketEntry();
			$entry->scoreboardId = $in->getVarLong();
			if($this->type === self::TYPE_REGISTER_IDENTITY){
				$entry->actorUniqueId = $in->getActorUniqueId();
			}

			$this->entries[] = $entry;
		}
	}

	protected function encodePayload(PacketSerializer $out) : void{
		$out->putByte($this->type);
		$out->putUnsignedVarInt(count($this->entries));
		foreach($this->entries as $entry){
			$out->putVarLong($entry->scoreboardId);
			if($this->type === self::TYPE_REGISTER_IDENTITY){
				$out->putActorUniqueId($entry->actorUniqueId);
			}
		}
	}

	public function handle(PacketHandlerInterface $handler) : bool{
		return $handler->handleSetScoreboardIdentity($this);
	}
}
