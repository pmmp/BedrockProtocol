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

use pocketmine\nbt\tag\CompoundTag;
use pocketmine\nbt\TreeRoot;
use pocketmine\network\mcpe\protocol\serializer\NetworkNbtSerializer;
use pocketmine\network\mcpe\protocol\serializer\PacketSerializer;

class SyncActorPropertyPacket extends DataPacket{
	public const NETWORK_ID = ProtocolInfo::SYNC_ACTOR_PROPERTY_PACKET;

	private CompoundTag $nbt;

	/**
	 * @generate-create-func
	 */
	public static function create(CompoundTag $nbt) : self{
		$result = new self;
		$result->nbt = $nbt;
		return $result;
	}

	public function getNbt() : CompoundTag{ return $this->nbt; }

	protected function decodePayload(PacketSerializer $in) : void{
		$this->nbt = $in->getNbtCompoundRoot();
	}

	protected function encodePayload(PacketSerializer $out) : void{
		$out->put((new NetworkNbtSerializer())->write(new TreeRoot($this->nbt)));
	}

	public function handle(PacketHandlerInterface $handler) : bool{
		return $handler->handleSyncActorProperty($this);
	}
}
