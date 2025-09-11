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

class ChangeDimensionPacket extends DataPacket implements ClientboundPacket{
	public const NETWORK_ID = ProtocolInfo::CHANGE_DIMENSION_PACKET;

	public int $dimension;
	public Vector3 $position;
	public bool $respawn = false;
	private ?int $loadingScreenId = null;

	/**
	 * @generate-create-func
	 */
	public static function create(int $dimension, Vector3 $position, bool $respawn, ?int $loadingScreenId) : self{
		$result = new self;
		$result->dimension = $dimension;
		$result->position = $position;
		$result->respawn = $respawn;
		$result->loadingScreenId = $loadingScreenId;
		return $result;
	}

	public function getLoadingScreenId() : ?int{ return $this->loadingScreenId; }

	protected function decodePayload(ByteBufferReader $in) : void{
		$this->dimension = VarInt::readSignedInt($in);
		$this->position = CommonTypes::getVector3($in);
		$this->respawn = CommonTypes::getBool($in);
		$this->loadingScreenId = CommonTypes::readOptional($in, LE::readUnsignedInt(...));
	}

	protected function encodePayload(ByteBufferWriter $out) : void{
		VarInt::writeSignedInt($out, $this->dimension);
		CommonTypes::putVector3($out, $this->position);
		CommonTypes::putBool($out, $this->respawn);
		CommonTypes::writeOptional($out, $this->loadingScreenId, LE::writeUnsignedInt(...));
	}

	public function handle(PacketHandlerInterface $handler) : bool{
		return $handler->handleChangeDimension($this);
	}
}
