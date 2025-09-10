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
use pocketmine\network\mcpe\protocol\serializer\CommonTypes;
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

	protected function decodePayload(ByteBufferReader $in) : void{
		$this->gameRules = CommonTypes::getGameRules($in);
	}

	protected function encodePayload(ByteBufferWriter $out) : void{
		CommonTypes::putGameRules($out, $this->gameRules);
	}

	public function handle(PacketHandlerInterface $handler) : bool{
		return $handler->handleGameRulesChanged($this);
	}
}
