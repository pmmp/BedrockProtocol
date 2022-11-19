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

use pocketmine\math\Vector3;

class StructureSettings{

	public string $paletteName;
	public bool $ignoreEntities;
	public bool $ignoreBlocks;
	public bool $allowNonTickingChunks;
	public BlockPosition $dimensions;
	public BlockPosition $offset;
	public int $lastTouchedByPlayerID;
	public int $rotation;
	public int $mirror;
	public int $animationMode;
	public float $animationSeconds;
	public float $integrityValue;
	public int $integritySeed;
	public Vector3 $pivot;
}
