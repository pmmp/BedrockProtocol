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

/**
 * @see ClientboundTextureShiftPacket
 */
final class TextureShiftAction{
	public const INVALID = 0;
	public const INITIALIZE = 1;
	public const START = 2;
	public const SET_ENABLED = 3;
	public const SYNC = 4;
}
