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

class AnimationType {
	public const SWIM = "minecraft:swim";
	public const WALK = "minecraft:walk";
	public const RUN = "minecraft:run";
	public const JUMP = "minecraft:jump";
	public const ATTACK = "minecraft:attack";
	public const HURT = "minecraft:hurt";
	public const DEATH = "minecraft:death";
	public const EAT = "minecraft:eat";
	public const SLEEP = "minecraft:sleep";
}
