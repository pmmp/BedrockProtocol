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
use pocketmine\network\mcpe\protocol\types\GameRule;

class GameRulesChangedPacket extends DataPacket implements ClientboundPacket{
	public const NETWORK_ID = ProtocolInfo::GAME_RULES_CHANGED_PACKET;

	/**
	 * @var GameRule[]
	 * @phpstan-var array<string, GameRule>
	 */
	public array $gameRules = [];

	/**
	 * @generate-create-func
	 * @param GameRule[] $gameRules
	 * @phpstan-param array<string, GameRule> $gameRules
	 */
	public static function create(array $gameRules) : self{
		$result = new self;
		$result->gameRules = $gameRules;
		return $result;
	}

	protected function decodePayload(PacketSerializer $in) : void{
		$this->gameRules = $in->getGameRules();
	}

	protected function encodePayload(PacketSerializer $out) : void{
		$out->putGameRules($this->gameRules);
	}

	public function handle(PacketHandlerInterface $handler) : bool{
		return $handler->handleGameRulesChanged($this);
	}
}
