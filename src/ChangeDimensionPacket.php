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

class ChangeDimensionPacket extends DataPacket implements ClientboundPacket{
	public const NETWORK_ID = ProtocolInfo::CHANGE_DIMENSION_PACKET;

	public int $dimension;
	public Vector3 $position;
	public bool $respawn = false;

	/**
	 * @generate-create-func
	 */
	public static function create(int $dimension, Vector3 $position, bool $respawn) : self{
		$result = new self;
		$result->dimension = $dimension;
		$result->position = $position;
		$result->respawn = $respawn;
		return $result;
	}

	protected function decodePayload(PacketSerializer $in) : void{
		$this->dimension = $in->getVarInt();
		$this->position = $in->getVector3();
		$this->respawn = $in->getBool();
	}

	protected function encodePayload(PacketSerializer $out) : void{
		$out->putVarInt($this->dimension);
		$out->putVector3($this->position);
		$out->putBool($this->respawn);
	}

	public function handle(PacketHandlerInterface $handler) : bool{
		return $handler->handleChangeDimension($this);
	}
}
