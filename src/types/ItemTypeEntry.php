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

final class ItemTypeEntry{
	/**
	 * @phpstan-param CacheableNbt<\pocketmine\nbt\tag\CompoundTag> $componentNbt
	 */
	public function __construct(
		private string $stringId,
		private int $numericId,
		private bool $componentBased,
		private int $version,
		private CacheableNbt $componentNbt
	){}

	public function getStringId() : string{ return $this->stringId; }

	public function getNumericId() : int{ return $this->numericId; }

	public function isComponentBased() : bool{ return $this->componentBased; }

	public function getVersion() : int{ return $this->version; }

	/** @phpstan-return CacheableNbt<\pocketmine\nbt\tag\CompoundTag> */
	public function getComponentNbt() : CacheableNbt{ return $this->componentNbt; }
}
