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

	protected function decodePayload(ByteBufferReader $in) : void{
		$this->actorUniqueId = CommonTypes::getActorUniqueId($in);
		$this->actorRuntimeId = CommonTypes::getActorRuntimeId($in);
		$this->position = CommonTypes::getVector3($in);
		$this->direction = VarInt::readSignedInt($in);
		$this->title = CommonTypes::getString($in);
	}

	protected function encodePayload(ByteBufferWriter $out) : void{
		CommonTypes::putActorUniqueId($out, $this->actorUniqueId);
		CommonTypes::putActorRuntimeId($out, $this->actorRuntimeId);
		CommonTypes::putVector3($out, $this->position);
		VarInt::writeSignedInt($out, $this->direction);
		CommonTypes::putString($out, $this->title);
	}

	public function handle(PacketHandlerInterface $handler) : bool{
		return $handler->handleAddPainting($this);
	}
}
