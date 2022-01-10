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

use pocketmine\math\Vector3;
use pocketmine\network\mcpe\protocol\serializer\PacketSerializer;

/**
 * Useless leftover from a 1.8 refactor, does nothing
 */
class LevelSoundEventPacketV1 extends DataPacket{
	public const NETWORK_ID = ProtocolInfo::LEVEL_SOUND_EVENT_PACKET_V1;

	public int $sound;
	public Vector3 $position;
	public int $extraData = 0;
	public int $entityType = 1;
	public bool $isBabyMob = false; //...
	public bool $disableRelativeVolume = false;

	/**
	 * @generate-create-func
	 */
	public static function create(int $sound, Vector3 $position, int $extraData, int $entityType, bool $isBabyMob, bool $disableRelativeVolume) : self{
		$result = new self;
		$result->sound = $sound;
		$result->position = $position;
		$result->extraData = $extraData;
		$result->entityType = $entityType;
		$result->isBabyMob = $isBabyMob;
		$result->disableRelativeVolume = $disableRelativeVolume;
		return $result;
	}

	protected function decodePayload(PacketSerializer $in) : void{
		$this->sound = $in->getByte();
		$this->position = $in->getVector3();
		$this->extraData = $in->getVarInt();
		$this->entityType = $in->getVarInt();
		$this->isBabyMob = $in->getBool();
		$this->disableRelativeVolume = $in->getBool();
	}

	protected function encodePayload(PacketSerializer $out) : void{
		$out->putByte($this->sound);
		$out->putVector3($this->position);
		$out->putVarInt($this->extraData);
		$out->putVarInt($this->entityType);
		$out->putBool($this->isBabyMob);
		$out->putBool($this->disableRelativeVolume);
	}

	public function handle(PacketHandlerInterface $handler) : bool{
		return $handler->handleLevelSoundEventPacketV1($this);
	}
}
