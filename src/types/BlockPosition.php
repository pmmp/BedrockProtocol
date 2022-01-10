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

final class BlockPosition{

	public function __construct(
		private int $x,
		private int $y,
		private int $z
	){}

	public function getX() : int{ return $this->x; }

	public function getY() : int{ return $this->y; }

	public function getZ() : int{ return $this->z; }

	public static function fromVector3(Vector3 $vector3) : self{
		return new self($vector3->getFloorX(), $vector3->getFloorY(), $vector3->getFloorZ());
	}

	public function equals(BlockPosition $other) : bool{
		return $this->x === $other->x && $this->y === $other->y && $this->z === $other->z;
	}
}
