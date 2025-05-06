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

final class BiomeMultinoiseGenRulesData{

	public function __construct(
		private float $temperature,
		private float $humidity,
		private float $altitude,
		private float $weirdness,
		private float $weight,
	){}

	public function getTemperature() : float{ return $this->temperature; }

	public function getHumidity() : float{ return $this->humidity; }

	public function getAltitude() : float{ return $this->altitude; }

	public function getWeirdness() : float{ return $this->weirdness; }

	public function getWeight() : float{ return $this->weight; }

	public static function read(PacketSerializer $in) : self{
		$temperature = $in->getLFloat();
		$humidity = $in->getLFloat();
		$altitude = $in->getLFloat();
		$weirdness = $in->getLFloat();
		$weight = $in->getLFloat();

		return new self(
			$temperature,
			$humidity,
			$altitude,
			$weirdness,
			$weight
		);
	}

	public function write(PacketSerializer $out) : void{
		$out->putLFloat($this->temperature);
		$out->putLFloat($this->humidity);
		$out->putLFloat($this->altitude);
		$out->putLFloat($this->weirdness);
		$out->putLFloat($this->weight);
	}
}
