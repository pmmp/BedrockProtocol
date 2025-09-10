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

class LegacyTelemetryEventPacket extends DataPacket implements ClientboundPacket{
	public const NETWORK_ID = ProtocolInfo::LEGACY_TELEMETRY_EVENT_PACKET;

	public const TYPE_ACHIEVEMENT_AWARDED = 0;
	public const TYPE_ENTITY_INTERACT = 1;
	public const TYPE_PORTAL_BUILT = 2;
	public const TYPE_PORTAL_USED = 3;
	public const TYPE_MOB_KILLED = 4;
	public const TYPE_CAULDRON_USED = 5;
	public const TYPE_PLAYER_DEATH = 6;
	public const TYPE_BOSS_KILLED = 7;
	public const TYPE_AGENT_COMMAND = 8;
	public const TYPE_AGENT_CREATED = 9;
	public const TYPE_PATTERN_REMOVED = 10; //???
	public const TYPE_COMMANED_EXECUTED = 11;
	public const TYPE_FISH_BUCKETED = 12;
	public const TYPE_MOB_BORN = 13;
	public const TYPE_PET_DIED = 14;
	public const TYPE_CAULDRON_BLOCK_USED = 15;
	public const TYPE_COMPOSTER_BLOCK_USED = 16;
	public const TYPE_BELL_BLOCK_USED = 17;
	public const TYPE_ACTOR_DEFINITION = 18;
	public const TYPE_RAID_UPDATE = 19;
	public const TYPE_PLAYER_MOVEMENT_ANOMALY = 20; //anti cheat
	public const TYPE_PLAYER_MOVEMENT_CORRECTED = 21;
	public const TYPE_HONEY_HARVESTED = 22;
	public const TYPE_TARGET_BLOCK_HIT = 23;
	public const TYPE_PIGLIN_BARTER = 24;

	public int $playerRuntimeId;
	public int $eventData;
	public int $type;

	protected function decodePayload(ByteBufferReader $in) : void{
		$this->playerRuntimeId = CommonTypes::getActorRuntimeId($in);
		$this->eventData = VarInt::readSignedInt($in);
		$this->type = Byte::readUnsigned($in);

		//TODO: nice confusing mess
	}

	protected function encodePayload(ByteBufferWriter $out) : void{
		CommonTypes::putActorRuntimeId($out, $this->playerRuntimeId);
		VarInt::writeSignedInt($out, $this->eventData);
		Byte::writeUnsigned($out, $this->type);

		//TODO: also nice confusing mess
	}

	public function handle(PacketHandlerInterface $handler) : bool{
		return $handler->handleLegacyTelemetryEvent($this);
	}
}
