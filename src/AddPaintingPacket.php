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

class AddPaintingPacket extends DataPacket implements ClientboundPacket{
	public const NETWORK_ID = ProtocolInfo::ADD_PAINTING_PACKET;

	public int $actorUniqueId;
	public int $actorRuntimeId;
	public Vector3 $position;
	public int $direction;
	public string $title;

	/**
	 * @generate-create-func
	 */
	public static function create(int $actorUniqueId, int $actorRuntimeId, Vector3 $position, int $direction, string $title) : self{
		$result = new self;
		$result->actorUniqueId = $actorUniqueId;
		$result->actorRuntimeId = $actorRuntimeId;
		$result->position = $position;
		$result->direction = $direction;
		$result->title = $title;
		return $result;
	}

	protected function decodePayload(PacketSerializer $in) : void{
		$this->actorUniqueId = $in->getActorUniqueId();
		$this->actorRuntimeId = $in->getActorRuntimeId();
		$this->position = $in->getVector3();
		$this->direction = $in->getVarInt();
		$this->title = $in->getString();
	}

	protected function encodePayload(PacketSerializer $out) : void{
		$out->putActorUniqueId($this->actorUniqueId);
		$out->putActorRuntimeId($this->actorRuntimeId);
		$out->putVector3($this->position);
		$out->putVarInt($this->direction);
		$out->putString($this->title);
	}

	public function handle(PacketHandlerInterface $handler) : bool{
		return $handler->handleAddPainting($this);
	}
}
