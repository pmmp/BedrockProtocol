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

final class AgentActionType{

	private function __construct(){
		//NOOP
	}

	public const ATTACK = 1;
	public const COLLECT = 2;
	public const DESTROY = 3;
	public const DETECT_REDSTONE = 4;
	public const DETECT_OBSTACLE = 5;
	public const DROP = 6;
	public const DROP_ALL = 7;
	public const INSPECT = 8;
	public const INSPECT_DATA = 9;
	public const INSPECT_ITEM_COUNT = 10;
	public const INSPECT_ITEM_DETAIL = 11;
	public const INSPECT_ITEM_SPACE = 12;
	public const INTERACT = 13;
	public const MOVE = 14;
	public const PLACE_BLOCK = 15;
	public const TILL = 16;
	public const TRANSFER_ITEM_TO = 17;
	public const TURN = 18;
}
