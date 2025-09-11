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
use pocketmine\network\mcpe\protocol\serializer\CommonTypes;

class CreatePhotoPacket extends DataPacket implements ServerboundPacket{
	public const NETWORK_ID = ProtocolInfo::CREATE_PHOTO_PACKET;

	private int $actorUniqueId;
	private string $photoName;
	private string $photoItemName;

	/**
	 * @generate-create-func
	 */
	public static function create(int $actorUniqueId, string $photoName, string $photoItemName) : self{
		$result = new self;
		$result->actorUniqueId = $actorUniqueId;
		$result->photoName = $photoName;
		$result->photoItemName = $photoItemName;
		return $result;
	}

	public function getActorUniqueId() : int{ return $this->actorUniqueId; }

	public function getPhotoName() : string{ return $this->photoName; }

	public function getPhotoItemName() : string{ return $this->photoItemName; }

	protected function decodePayload(ByteBufferReader $in) : void{
		$this->actorUniqueId = LE::readSignedLong($in); //why be consistent mojang ?????
		$this->photoName = CommonTypes::getString($in);
		$this->photoItemName = CommonTypes::getString($in);
	}

	protected function encodePayload(ByteBufferWriter $out) : void{
		LE::writeSignedLong($out, $this->actorUniqueId);
		CommonTypes::putString($out, $this->photoName);
		CommonTypes::putString($out, $this->photoItemName);
	}

	public function handle(PacketHandlerInterface $handler) : bool{
		return $handler->handleCreatePhoto($this);
	}
}
