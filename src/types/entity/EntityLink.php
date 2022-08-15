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

namespace pocketmine\network\mcpe\protocol\types\entity;

class EntityLink{

	public const TYPE_REMOVE = 0;
	public const TYPE_RIDER = 1;
	public const TYPE_PASSENGER = 2;

	public function __construct(
		public int $fromActorUniqueId,
		public int $toActorUniqueId,
		public int $type,
		public bool $immediate,
		public bool $causedByRider
	){}
}
