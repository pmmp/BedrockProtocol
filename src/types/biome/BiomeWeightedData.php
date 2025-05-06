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

namespace pocketmine\network\mcpe\protocol\types\biome;

use pocketmine\network\mcpe\protocol\serializer\PacketSerializer;

final class BiomeWeightedData{

	public function __construct(
		private int $biome,
		private int $weight,
	){}

	public function getBiome() : int{ return $this->biome; }

	public function getWeight() : int{ return $this->weight; }

	public static function read(PacketSerializer $in) : self{
		$biome = $in->getLShort();
		$weight = $in->getLInt();

		return new self(
			$biome,
			$weight
		);
	}

	public function write(PacketSerializer $out) : void{
		$out->putLShort($this->biome);
		$out->putLInt($this->weight);
	}
}
