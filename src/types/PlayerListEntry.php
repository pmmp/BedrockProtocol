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

namespace pocketmine\network\mcpe\protocol\types;

use pocketmine\network\mcpe\protocol\types\skin\SkinData;
use Ramsey\Uuid\UuidInterface;

class PlayerListEntry{

	public UuidInterface $uuid;
	public int $actorUniqueId;
	public string $username;
	public SkinData $skinData;
	public string $xboxUserId;
	public string $platformChatId = "";
	public int $buildPlatform = DeviceOS::UNKNOWN;
	public bool $isTeacher = false;
	public bool $isHost = false;

	public static function createRemovalEntry(UuidInterface $uuid) : PlayerListEntry{
		$entry = new PlayerListEntry();
		$entry->uuid = $uuid;

		return $entry;
	}

	public static function createAdditionEntry(UuidInterface $uuid, int $actorUniqueId, string $username, SkinData $skinData, string $xboxUserId = "", string $platformChatId = "", int $buildPlatform = -1, bool $isTeacher = false, bool $isHost = false) : PlayerListEntry{
		$entry = new PlayerListEntry();
		$entry->uuid = $uuid;
		$entry->actorUniqueId = $actorUniqueId;
		$entry->username = $username;
		$entry->skinData = $skinData;
		$entry->xboxUserId = $xboxUserId;
		$entry->platformChatId = $platformChatId;
		$entry->buildPlatform = $buildPlatform;
		$entry->isTeacher = $isTeacher;
		$entry->isHost = $isHost;

		return $entry;
	}
}
