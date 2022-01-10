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

final class GameMode{

	private function __construct(){
		//NOOP
	}

	public const SURVIVAL = 0;
	public const CREATIVE = 1;
	public const ADVENTURE = 2;
	public const SURVIVAL_VIEWER = 3;
	public const CREATIVE_VIEWER = 4;
	public const DEFAULT = 5;
}
