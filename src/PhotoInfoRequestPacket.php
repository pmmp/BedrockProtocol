<?php

/*
 *
 *  ____            _        _   __  __ _                  __  __ ____
 * |  _ \ ___   ___| | _____| |_|  \/  (_)_ __   ___      |  \/  |  _ \
 * | |_) / _ \ / __| |/ / _ \ __| |\/| | | '_ \ / _ \_____| |\/| | |_) |
 * |  __/ (_) | (__|   <  __/ |_| |  | | | | | |  __/_____| |  | |  __/
 * |_|   \___/ \___|_|\_\___|\__|_|  |_|_|_| |_|\___|     |_|  |_|_|
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * @author PocketMine Team
 * @link http://www.pocketmine.net/
 *
 *
*/

declare(strict_types=1);

namespace pocketmine\network\mcpe\protocol;

#include <rules/DataPacket.h>

use pocketmine\network\mcpe\protocol\serializer\PacketSerializer;

/**
 * TODO: this one has no handlers, so I have no idea which direction it should be sent
 * It doesn't appear to be used at all right now... this is just here to keep the scraper happy
 */
class PhotoInfoRequestPacket extends DataPacket{
	public const NETWORK_ID = ProtocolInfo::PHOTO_INFO_REQUEST_PACKET;

	private int $photoId;

	public static function create(int $photoId) : self{
		$result = new self;
		$result->photoId = $photoId;
		return $result;
	}

	protected function decodePayload(PacketSerializer $in) : void{
		$this->photoId = $in->getEntityUniqueId();
	}

	protected function encodePayload(PacketSerializer $out) : void{
		$out->putEntityUniqueId($this->photoId);
	}

	public function handle(PacketHandlerInterface $handler) : bool{
		return $handler->handlePhotoInfoRequest($this);
	}
}
