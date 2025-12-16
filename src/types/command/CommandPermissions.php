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

use function array_flip;

final class CommandPermissions{
	private function __construct(){
		//NOOP
	}

	public const NORMAL = 0;
	public const OPERATOR = 1;
	public const AUTOMATION = 2; //command blocks
	public const HOST = 3; //hosting player on LAN multiplayer
	public const OWNER = 4; //server terminal on BDS
	public const INTERNAL = 5;

	private const PERMISSION_NAMES = [
		0 => "any",
		1 => "gamedirectors",
		2 => "admin",
		3 => "host",
		4 => "owner",
		5 => "internal",
	];

	public static function toName(int $value) : string{
		return self::PERMISSION_NAMES[$value] ?? throw new \InvalidArgumentException("Invalid raw value \"$value\" for CommandPermission");
	}

	public static function fromName(string $name) : int{
		static $cache = null;
		if($cache === null){
			$cache = array_flip(self::PERMISSION_NAMES);
		}

		return $cache[$name] ?? throw new \InvalidArgumentException("Invalid raw value \"$name\" for CommandPermission");
	}
}
