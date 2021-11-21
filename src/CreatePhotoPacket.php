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
