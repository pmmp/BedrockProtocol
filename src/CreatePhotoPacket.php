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

use pocketmine\network\mcpe\protocol\serializer\PacketSerializer;

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

	protected function decodePayload(PacketSerializer $in) : void{
		$this->actorUniqueId = $in->getLLong(); //why be consistent mojang ?????
		$this->photoName = $in->getString();
		$this->photoItemName = $in->getString();
	}

	protected function encodePayload(PacketSerializer $out) : void{
		$out->putLLong($this->actorUniqueId);
		$out->putString($this->photoName);
		$out->putString($this->photoItemName);
	}

	public function handle(PacketHandlerInterface $handler) : bool{
		return $handler->handleCreatePhoto($this);
	}
}
