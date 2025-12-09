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

namespace pocketmine\network\mcpe\protocol\types\command;

use pocketmine\network\mcpe\protocol\PacketDecodeException;
use pocketmine\network\mcpe\protocol\types\PacketIntEnumTrait;
use function array_flip;

enum CommandPermissions : int{
	use PacketIntEnumTrait;

	case NORMAL = 0;
	case OPERATOR = 1;
	case AUTOMATION = 2; //command blocks
	case HOST = 3; //hosting player on LAN multiplayer
	case OWNER = 4; //server terminal on BDS
	case INTERNAL = 5;

	private const PERMISSION_NAMES = [ // enum case references requires PHP 8.2
		0 => "any",
		1 => "gamedirectors",
		2 => "admin",
		3 => "host",
		4 => "owner",
		5 => "internal",
	];

	public function getPermissionName() : string{
		return self::PERMISSION_NAMES[$this->value];
	}

	public static function fromPermissionName(string $name) : self{
		static $cache = null;
		if($cache === null){
			$cache = array_flip(self::PERMISSION_NAMES);
		}

		$value = $cache[$name] ?? throw new PacketDecodeException("Invalid raw value $name for " . static::class);

		return self::fromPacket($value);
	}
}
