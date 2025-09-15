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
use pocketmine\math\Vector3;
use pocketmine\network\mcpe\protocol\serializer\CommonTypes;
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

	protected function decodePayload(ByteBufferReader $in) : void{
		$this->dimensionId = Byte::readUnsigned($in);
		$this->actorUniqueId = CommonTypes::getActorUniqueId($in);
		$this->position = CommonTypes::getVector3($in);
		$this->particleName = CommonTypes::getString($in);
		$this->molangVariablesJson = CommonTypes::getBool($in) ? CommonTypes::getString($in) : null;
	}

	protected function encodePayload(ByteBufferWriter $out) : void{
		Byte::writeUnsigned($out, $this->dimensionId);
		CommonTypes::putActorUniqueId($out, $this->actorUniqueId);
		CommonTypes::putVector3($out, $this->position);
		CommonTypes::putString($out, $this->particleName);
		CommonTypes::putBool($out, $this->molangVariablesJson !== null);
		if($this->molangVariablesJson !== null){
			CommonTypes::putString($out, $this->molangVariablesJson);
		}
	}

	public function handle(PacketHandlerInterface $handler) : bool{
		return $handler->handleSpawnParticleEffect($this);
	}
}
