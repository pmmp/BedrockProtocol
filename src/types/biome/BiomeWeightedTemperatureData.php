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

final class BiomeWeightedTemperatureData{

	public function __construct(
		private int $temperature,
		private int $weight,
	){}

	public function getTemperature() : int{ return $this->temperature; }

	public function getWeight() : int{ return $this->weight; }

	public static function read(PacketSerializer $in) : self{
		$temperature = $in->getVarInt();
		$weight = $in->getLInt();

		return new self(
			$temperature,
			$weight
		);
	}

	public function write(PacketSerializer $out) : void{
		$out->putVarInt($this->temperature);
		$out->putLInt($this->weight);
	}
}
