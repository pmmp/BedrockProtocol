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

use pocketmine\network\mcpe\protocol\ClientboundControlSchemeSetPacket;

/**
 * @see ClientboundControlSchemeSetPacket
 */
enum ControlScheme : int{
	use PacketIntEnumTrait;

	case LOCKED_PLAYER_RELATIVE_STRAFE = 0;
	case CAMERA_RELATIVE = 1;
	case CAMERA_RELATIVE_STRAFE = 2;
	case PLAYER_RELATIVE = 3;
	case PLAYER_RELATIVE_STRAFE = 4;
}
