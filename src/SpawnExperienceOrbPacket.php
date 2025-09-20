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
use pmmp\encoding\VarInt;
use pocketmine\math\Vector3;
use pocketmine\network\mcpe\protocol\serializer\CommonTypes;

class SpawnExperienceOrbPacket extends DataPacket implements ServerboundPacket{
	public const NETWORK_ID = ProtocolInfo::SPAWN_EXPERIENCE_ORB_PACKET;

	public Vector3 $position;
	public int $amount;

	/**
	 * @generate-create-func
	 */
	public static function create(Vector3 $position, int $amount) : self{
		$result = new self;
		$result->position = $position;
		$result->amount = $amount;
		return $result;
	}

	protected function decodePayload(ByteBufferReader $in) : void{
		$this->position = CommonTypes::getVector3($in);
		$this->amount = VarInt::readSignedInt($in);
	}

	protected function encodePayload(ByteBufferWriter $out) : void{
		CommonTypes::putVector3($out, $this->position);
		VarInt::writeSignedInt($out, $this->amount);
	}

	public function handle(PacketHandlerInterface $handler) : bool{
		return $handler->handleSpawnExperienceOrb($this);
	}
}
