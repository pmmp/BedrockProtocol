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

use pocketmine\network\mcpe\protocol\serializer\PacketSerializer;

final class SpawnSettings{
	public const BIOME_TYPE_DEFAULT = 0;
	public const BIOME_TYPE_USER_DEFINED = 1;

	public function __construct(
		private int $biomeType,
		private string $biomeName,
		private int $dimension
	){}

	public function getBiomeType() : int{
		return $this->biomeType;
	}

	public function getBiomeName() : string{
		return $this->biomeName;
	}

	/**
	 * @see DimensionIds
	 */
	public function getDimension() : int{
		return $this->dimension;
	}

	public static function read(PacketSerializer $in) : self{
		$biomeType = $in->getLShort();
		$biomeName = $in->getString();
		$dimension = $in->getVarInt();

		return new self($biomeType, $biomeName, $dimension);
	}

	public function write(PacketSerializer $out) : void{
		$out->putLShort($this->biomeType);
		$out->putString($this->biomeName);
		$out->putVarInt($this->dimension);
	}
}
