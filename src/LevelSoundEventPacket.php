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
use pmmp\encoding\LE;
use pmmp\encoding\VarInt;
use pocketmine\math\Vector3;
use pocketmine\network\mcpe\protocol\serializer\CommonTypes;
use pocketmine\network\mcpe\protocol\types\LevelSoundEvent;

class LevelSoundEventPacket extends DataPacket implements ClientboundPacket, ServerboundPacket{
	public const NETWORK_ID = ProtocolInfo::LEVEL_SOUND_EVENT_PACKET;

	/** @see LevelSoundEvent */
	public int $sound;
	public Vector3 $position;
	public int $extraData = -1;
	public string $entityType = ":"; //???
	public bool $isBabyMob = false; //...
	public bool $disableRelativeVolume = false;
	public int $actorUniqueId = -1;

	/**
	 * @generate-create-func
	 */
	public static function create(
		int $sound,
		Vector3 $position,
		int $extraData,
		string $entityType,
		bool $isBabyMob,
		bool $disableRelativeVolume,
		int $actorUniqueId,
	) : self{
		$result = new self;
		$result->sound = $sound;
		$result->position = $position;
		$result->extraData = $extraData;
		$result->entityType = $entityType;
		$result->isBabyMob = $isBabyMob;
		$result->disableRelativeVolume = $disableRelativeVolume;
		$result->actorUniqueId = $actorUniqueId;
		return $result;
	}

	public static function nonActorSound(int $sound, Vector3 $position, bool $disableRelativeVolume, int $extraData = -1) : self{
		return self::create($sound, $position, $extraData, ":", false, $disableRelativeVolume, -1);
	}

	protected function decodePayload(ByteBufferReader $in) : void{
		$this->sound = VarInt::readUnsignedInt($in);
		$this->position = CommonTypes::getVector3($in);
		$this->extraData = VarInt::readSignedInt($in);
		$this->entityType = CommonTypes::getString($in);
		$this->isBabyMob = CommonTypes::getBool($in);
		$this->disableRelativeVolume = CommonTypes::getBool($in);
		$this->actorUniqueId = LE::readSignedLong($in); //WHY IS THIS NON-STANDARD?
	}

	protected function encodePayload(ByteBufferWriter $out) : void{
		VarInt::writeUnsignedInt($out, $this->sound);
		CommonTypes::putVector3($out, $this->position);
		VarInt::writeSignedInt($out, $this->extraData);
		CommonTypes::putString($out, $this->entityType);
		CommonTypes::putBool($out, $this->isBabyMob);
		CommonTypes::putBool($out, $this->disableRelativeVolume);
		LE::writeSignedLong($out, $this->actorUniqueId);
	}

	public function handle(PacketHandlerInterface $handler) : bool{
		return $handler->handleLevelSoundEvent($this);
	}
}
