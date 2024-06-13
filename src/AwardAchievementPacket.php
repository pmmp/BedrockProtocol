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

class AwardAchievementPacket extends DataPacket implements ClientboundPacket{
	public const NETWORK_ID = ProtocolInfo::AWARD_ACHIEVEMENT_PACKET;

	private int $achievementId;

	/**
	 * @generate-create-func
	 */
	public static function create(int $achievementId) : self{
		$result = new self;
		$result->achievementId = $achievementId;
		return $result;
	}

	public function getAchievementId() : int{ return $this->achievementId; }

	protected function decodePayload(PacketSerializer $in) : void{
		$this->achievementId = $in->getLInt();
	}

	protected function encodePayload(PacketSerializer $out) : void{
		$out->putLInt($this->achievementId);
	}

	public function handle(PacketHandlerInterface $handler) : bool{
		return $handler->handleAwardAchievement($this);
	}
}
