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

namespace pocketmine\network\mcpe\protocol\types\resourcepacks;

final class ResourcePackType{

	private function __construct(){
		//NOOP
	}

	public const INVALID = 0;
	public const ADDON = 1;
	public const CACHED = 2;
	public const COPY_PROTECTED = 3;
	public const BEHAVIORS = 4;
	public const PERSONA_PIECE = 5;
	public const RESOURCES = 6;
	public const SKINS = 7;
	public const WORLD_TEMPLATE = 8;
}
