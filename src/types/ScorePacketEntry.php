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

namespace pocketmine\network\mcpe\protocol\types;

class ScorePacketEntry{
	public const TYPE_PLAYER = 1;
	public const TYPE_ENTITY = 2;
	public const TYPE_FAKE_PLAYER = 3;

	public int $scoreboardId;
	public string $objectiveName;
	public int $score;
	public int $type;
	/** @var int|null (if type entity or player) */
	public ?int $actorUniqueId;
	/** @var string|null (if type fake player) */
	public ?string $customName;
}
