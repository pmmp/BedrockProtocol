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
use pocketmine\network\mcpe\protocol\types\PlayerPermissions;

/**
 * Sent by the client to request that the server change permissions of a player. This could be itself or another player.
 * Used when toggling permission switches or changing a player's permission level in the pause menu.
 */
class RequestPermissionsPacket extends DataPacket implements ServerboundPacket{
	public const NETWORK_ID = ProtocolInfo::REQUEST_PERMISSIONS_PACKET;

	public const FLAG_BUILD = 1 << 0;
	public const FLAG_MINE = 1 << 1;
	public const FLAG_DOORS_AND_SWITCHES = 1 << 2;
	public const FLAG_OPEN_CONTAINERS = 1 << 3;
	public const FLAG_ATTACK_PLAYERS = 1 << 4;
	public const FLAG_ATTACK_MOBS = 1 << 5;
	public const FLAG_OPERATOR = 1 << 6;
	public const FLAG_TELEPORT = 1 << 7;

	private int $targetActorUniqueId;
	/** @see PlayerPermissions */
	private int $playerPermission;
	private int $customFlags;

	/**
	 * @generate-create-func
	 */
	public static function create(int $targetActorUniqueId, int $playerPermission, int $customFlags) : self{
		$result = new self;
		$result->targetActorUniqueId = $targetActorUniqueId;
		$result->playerPermission = $playerPermission;
		$result->customFlags = $customFlags;
		return $result;
	}

	public function getTargetActorUniqueId() : int{ return $this->targetActorUniqueId; }

	/** @see PlayerPermissions */
	public function getPlayerPermission() : int{ return $this->playerPermission; }

	public function getCustomFlags() : int{ return $this->customFlags; }

	protected function decodePayload(PacketSerializer $in) : void{
		$this->targetActorUniqueId = $in->getLLong();
		$this->playerPermission = $in->getVarInt();
		$this->customFlags = $in->getLShort();
	}

	protected function encodePayload(PacketSerializer $out) : void{
		$out->putLLong($this->targetActorUniqueId);
		$out->putVarInt($this->playerPermission);
		$out->putLShort($this->customFlags);
	}

	public function handle(PacketHandlerInterface $handler) : bool{
		return $handler->handleRequestPermissions($this);
	}
}
