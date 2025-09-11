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

namespace pocketmine\network\mcpe\protocol\types\biome\chunkgen;

use pmmp\encoding\ByteBufferReader;
use pmmp\encoding\ByteBufferWriter;
use pmmp\encoding\LE;

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

	public static function read(ByteBufferReader $in) : self{
		$temperature = LE::readFloat($in);
		$humidity = LE::readFloat($in);
		$altitude = LE::readFloat($in);
		$weirdness = LE::readFloat($in);
		$weight = LE::readFloat($in);

		return new self(
			$temperature,
			$humidity,
			$altitude,
			$weirdness,
			$weight
		);
	}

	public function write(ByteBufferWriter $out) : void{
		LE::writeFloat($out, $this->temperature);
		LE::writeFloat($out, $this->humidity);
		LE::writeFloat($out, $this->altitude);
		LE::writeFloat($out, $this->weirdness);
		LE::writeFloat($out, $this->weight);
	}
}
