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

use pocketmine\nbt\tag\Tag;
use pocketmine\nbt\TreeRoot;
use pocketmine\network\mcpe\protocol\serializer\NetworkNbtSerializer;

/**
 * @phpstan-template TTagType of Tag
 */
final class CacheableNbt{
	private ?string $encodedNbt;

	/**
	 * @phpstan-param TTagType $nbtRoot
	 */
	public function __construct(
		private Tag $nbtRoot
	){}

	/**
	 * @phpstan-return TTagType
	 */
	public function getRoot() : Tag{
		return $this->nbtRoot;
	}

	public function getEncodedNbt() : string{
		return $this->encodedNbt ?? ($this->encodedNbt = (new NetworkNbtSerializer())->write(new TreeRoot($this->nbtRoot)));
	}
}
