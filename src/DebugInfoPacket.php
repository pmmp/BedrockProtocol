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

class DebugInfoPacket extends DataPacket implements ClientboundPacket, ServerboundPacket{
	public const NETWORK_ID = ProtocolInfo::DEBUG_INFO_PACKET;

	private int $entityUniqueId;
	private string $data;

	public static function create(int $entityUniqueId, string $data) : self{
		$result = new self;
		$result->entityUniqueId = $entityUniqueId;
		$result->data = $data;
		return $result;
	}

	public function getEntityUniqueId() : int{ return $this->entityUniqueId; }

	public function getData() : string{ return $this->data; }

	protected function decodePayload(PacketSerializer $in) : void{
		$this->entityUniqueId = $in->getEntityUniqueId();
		$this->data = $in->getString();
	}

	protected function encodePayload(PacketSerializer $out) : void{
		$out->putEntityUniqueId($this->entityUniqueId);
		$out->putString($this->data);
	}

	public function handle(PacketHandlerInterface $handler) : bool{
		return $handler->handleDebugInfo($this);
	}
}
