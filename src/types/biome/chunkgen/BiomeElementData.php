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
use pmmp\encoding\VarInt;

final class BiomeElementData{

	public function __construct(
		private float $noiseFrequencyScale,
		private float $noiseLowerBound,
		private float $noiseUpperBound,
		private int $heightMinType,
		private int $heightMin,
		private int $heightMaxType,
		private int $heightMax,
		private BiomeSurfaceMaterialData $surfaceMaterial,
	){}

	public function getNoiseFrequencyScale() : float{ return $this->noiseFrequencyScale; }

	public function getNoiseLowerBound() : float{ return $this->noiseLowerBound; }

	public function getNoiseUpperBound() : float{ return $this->noiseUpperBound; }

	public function getHeightMinType() : int{ return $this->heightMinType; }

	public function getHeightMin() : int{ return $this->heightMin; }

	public function getHeightMaxType() : int{ return $this->heightMaxType; }

	public function getHeightMax() : int{ return $this->heightMax; }

	public function getSurfaceMaterial() : BiomeSurfaceMaterialData{ return $this->surfaceMaterial; }

	public static function read(ByteBufferReader $in) : self{
		$noiseFrequencyScale = LE::readFloat($in);
		$noiseLowerBound = LE::readFloat($in);
		$noiseUpperBound = LE::readFloat($in);
		$heightMinType = VarInt::readSignedInt($in);
		$heightMin = LE::readSignedShort($in);
		$heightMaxType = VarInt::readSignedInt($in);
		$heightMax = LE::readSignedShort($in);
		$surfaceMaterial = BiomeSurfaceMaterialData::read($in);

		return new self(
			$noiseFrequencyScale,
			$noiseLowerBound,
			$noiseUpperBound,
			$heightMinType,
			$heightMin,
			$heightMaxType,
			$heightMax,
			$surfaceMaterial
		);
	}

	public function write(ByteBufferWriter $out) : void{
		LE::writeFloat($out, $this->noiseFrequencyScale);
		LE::writeFloat($out, $this->noiseLowerBound);
		LE::writeFloat($out, $this->noiseUpperBound);
		VarInt::writeSignedInt($out, $this->heightMinType);
		LE::writeSignedShort($out, $this->heightMin);
		VarInt::writeSignedInt($out, $this->heightMaxType);
		LE::writeSignedShort($out, $this->heightMax);
		$this->surfaceMaterial->write($out);
	}
}
