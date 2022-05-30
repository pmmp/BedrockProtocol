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
use pocketmine\network\mcpe\protocol\types\DimensionIds;

class SpawnParticleEffectPacket extends DataPacket implements ClientboundPacket{
	public const NETWORK_ID = ProtocolInfo::SPAWN_PARTICLE_EFFECT_PACKET;

	public int $dimensionId = DimensionIds::OVERWORLD; //wtf mojang
	public int $actorUniqueId = -1; //default none
	public Vector3 $position;
	public string $particleName;
	public ?string $molangVariablesJson = null;

	/**
	 * @generate-create-func
	 */
	public static function create(int $dimensionId, int $actorUniqueId, Vector3 $position, string $particleName, ?string $molangVariablesJson) : self{
		$result = new self;
		$result->dimensionId = $dimensionId;
		$result->actorUniqueId = $actorUniqueId;
		$result->position = $position;
		$result->particleName = $particleName;
		$result->molangVariablesJson = $molangVariablesJson;
		return $result;
	}

	protected function decodePayload(PacketSerializer $in) : void{
		$this->dimensionId = $in->getByte();
		$this->actorUniqueId = $in->getActorUniqueId();
		$this->position = $in->getVector3();
		$this->particleName = $in->getString();
		$this->molangVariablesJson = $in->getBool() ? $in->getString() : null;
	}

	protected function encodePayload(PacketSerializer $out) : void{
		$out->putByte($this->dimensionId);
		$out->putActorUniqueId($this->actorUniqueId);
		$out->putVector3($this->position);
		$out->putString($this->particleName);
		$out->putBool($this->molangVariablesJson !== null);
		if($this->molangVariablesJson !== null){
			$out->putString($this->molangVariablesJson);
		}
	}

	public function handle(PacketHandlerInterface $handler) : bool{
		return $handler->handleSpawnParticleEffect($this);
	}
}
