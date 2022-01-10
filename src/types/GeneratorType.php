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

final class GeneratorType{

	private function __construct(){
		//NOOP
	}

	public const FINITE_OVERWORLD = 0;
	public const OVERWORLD = 1;
	public const FLAT = 2;
	public const NETHER = 3;
	public const THE_END = 4;
}
