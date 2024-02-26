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

namespace pocketmine\network\mcpe\protocol\types\inventory;

use pocketmine\nbt\tag\CompoundTag;

/**
 * Wrapper class for extra data on ItemStacks.
 * The data is normally provided as a raw string (not automatically decoded).
 * This class is just a DTO for PacketSerializer to use when encoding/decoding ItemStacks.
 */
final class ItemStackExtraData{
	/**
	 * @param string[] $canPlaceOn
	 * @param string[] $canDestroy
	 */
	public function __construct(
		private ?CompoundTag $nbt,
		private array $canPlaceOn,
		private array $canDestroy,
		private ?int $shieldBlockingTick = null
	){}

	/**
	 * @return string[]
	 */
	public function getCanPlaceOn() : array{
		return $this->canPlaceOn;
	}

	/**
	 * @return string[]
	 */
	public function getCanDestroy() : array{
		return $this->canDestroy;
	}

	public function getNbt() : ?CompoundTag{
		return $this->nbt;
	}

	public function getShieldBlockingTick() : ?int{
		return $this->shieldBlockingTick;
	}
}
