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

use Ramsey\Uuid\UuidInterface;

class CommandOriginData{
	public const ORIGIN_PLAYER = 0;
	public const ORIGIN_BLOCK = 1;
	public const ORIGIN_MINECART_BLOCK = 2;
	public const ORIGIN_DEV_CONSOLE = 3;
	public const ORIGIN_TEST = 4;
	public const ORIGIN_AUTOMATION_PLAYER = 5;
	public const ORIGIN_CLIENT_AUTOMATION = 6;
	public const ORIGIN_DEDICATED_SERVER = 7;
	public const ORIGIN_ENTITY = 8;
	public const ORIGIN_VIRTUAL = 9;
	public const ORIGIN_GAME_ARGUMENT = 10;
	public const ORIGIN_ENTITY_SERVER = 11; //???

	public int $type;
	public UuidInterface $uuid;
	public string $requestId;
	public int $playerActorUniqueId;
}
