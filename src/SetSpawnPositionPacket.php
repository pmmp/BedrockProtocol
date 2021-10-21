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
use pocketmine\network\mcpe\protocol\types\BlockPosition;
use pocketmine\utils\Limits;

class SetSpawnPositionPacket extends DataPacket implements ClientboundPacket{
	public const NETWORK_ID = ProtocolInfo::SET_SPAWN_POSITION_PACKET;

	public const TYPE_PLAYER_SPAWN = 0;
	public const TYPE_WORLD_SPAWN = 1;

	public int $spawnType;
	public BlockPosition $spawnPosition;
	public int $dimension;
	public BlockPosition $spawnPosition2; //this has no obvious use

	public static function playerSpawn(BlockPosition $spawnPosition, int $dimension, BlockPosition $spawnPosition2) : self{
		$result = new self;
		$result->spawnType = self::TYPE_PLAYER_SPAWN;
		$result->spawnPosition = $spawnPosition;
		$result->spawnPosition2 = $spawnPosition2;
		$result->dimension = $dimension;
		return $result;
	}

	public static function worldSpawn(BlockPosition $spawnPosition, int $dimension) : self{
		$result = new self;
		$result->spawnType = self::TYPE_WORLD_SPAWN;
		$result->spawnPosition = $spawnPosition;
		$result->spawnPosition2 = new BlockPosition(Limits::INT32_MIN, Limits::INT32_MIN, Limits::INT32_MIN);
		$result->dimension = $dimension;
		return $result;
	}

	protected function decodePayload(PacketSerializer $in) : void{
		$this->spawnType = $in->getVarInt();
		$this->spawnPosition = $in->getBlockPosition();
		$this->dimension = $in->getVarInt();
		$this->spawnPosition2 = $in->getBlockPosition();
	}

	protected function encodePayload(PacketSerializer $out) : void{
		$out->putVarInt($this->spawnType);
		$out->putBlockPosition($this->spawnPosition);
		$out->putVarInt($this->dimension);
		$out->putBlockPosition($this->spawnPosition2);
	}

	public function handle(PacketHandlerInterface $handler) : bool{
		return $handler->handleSetSpawnPosition($this);
	}
}
