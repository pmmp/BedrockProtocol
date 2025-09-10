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

final class BiomeClimateData{

	public function __construct(
		private float $temperature,
		private float $downfall,
		private float $redSporeDensity,
		private float $blueSporeDensity,
		private float $ashDensity,
		private float $whiteAshDensity,
		private float $snowAccumulationMin,
		private float $snowAccumulationMax,
	){}

	public function getTemperature() : float{ return $this->temperature; }

	public function getDownfall() : float{ return $this->downfall; }

	public function getRedSporeDensity() : float{ return $this->redSporeDensity; }

	public function getBlueSporeDensity() : float{ return $this->blueSporeDensity; }

	public function getAshDensity() : float{ return $this->ashDensity; }

	public function getWhiteAshDensity() : float{ return $this->whiteAshDensity; }

	public function getSnowAccumulationMin() : float{ return $this->snowAccumulationMin; }

	public function getSnowAccumulationMax() : float{ return $this->snowAccumulationMax; }

	public static function read(ByteBufferReader $in) : self{
		$temperature = LE::readFloat($in);
		$downfall = LE::readFloat($in);
		$redSporeDensity = LE::readFloat($in);
		$blueSporeDensity = LE::readFloat($in);
		$ashDensity = LE::readFloat($in);
		$whiteAshDensity = LE::readFloat($in);
		$snowAccumulationMin = LE::readFloat($in);
		$snowAccumulationMax = LE::readFloat($in);

		return new self(
			$temperature,
			$downfall,
			$redSporeDensity,
			$blueSporeDensity,
			$ashDensity,
			$whiteAshDensity,
			$snowAccumulationMin,
			$snowAccumulationMax
		);
	}

	public function write(ByteBufferWriter $out) : void{
		LE::writeFloat($out, $this->temperature);
		LE::writeFloat($out, $this->downfall);
		LE::writeFloat($out, $this->redSporeDensity);
		LE::writeFloat($out, $this->blueSporeDensity);
		LE::writeFloat($out, $this->ashDensity);
		LE::writeFloat($out, $this->whiteAshDensity);
		LE::writeFloat($out, $this->snowAccumulationMin);
		LE::writeFloat($out, $this->snowAccumulationMax);
	}
}
