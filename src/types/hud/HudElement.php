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

namespace pocketmine\network\mcpe\protocol\types\hud;

use pocketmine\network\mcpe\protocol\types\PacketIntEnumTrait;

enum HudElement : int{
	use PacketIntEnumTrait;

	case PAPER_DOLL = 0;
	case ARMOR = 1;
	case TOOLTIPS = 2;
	case TOUCH_CONTROLS = 3;
	case CROSSHAIR = 4;
	case HOTBAR = 5;
	case HEALTH = 6;
	case XP = 7;
	case FOOD = 8;
	case AIR_BUBBLES = 9;
	case HORSE_HEALTH = 10;
	case STATUS_EFFECTS = 11;
	case ITEM_TEXT = 12;
}
