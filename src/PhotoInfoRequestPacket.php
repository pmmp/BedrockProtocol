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

/**
 * TODO: this one has no handlers, so I have no idea which direction it should be sent
 * It doesn't appear to be used at all right now... this is just here to keep the scraper happy
 */
class PhotoInfoRequestPacket extends DataPacket{
	public const NETWORK_ID = ProtocolInfo::PHOTO_INFO_REQUEST_PACKET;

	private int $photoId;

	/**
	 * @generate-create-func
	 */
	public static function create(int $photoId) : self{
		$result = new self;
		$result->photoId = $photoId;
		return $result;
	}

	public function getPhotoId() : int{ return $this->photoId; }

	protected function decodePayload(PacketSerializer $in) : void{
		$this->photoId = $in->getActorUniqueId();
	}

	protected function encodePayload(PacketSerializer $out) : void{
		$out->putActorUniqueId($this->photoId);
	}

	public function handle(PacketHandlerInterface $handler) : bool{
		return $handler->handlePhotoInfoRequest($this);
	}
}
