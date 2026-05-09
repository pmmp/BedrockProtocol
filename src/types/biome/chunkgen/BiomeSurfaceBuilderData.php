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
use pocketmine\network\mcpe\protocol\serializer\CommonTypes;

final class BiomeSurfaceBuilderData{

	public function __construct(
		private ?BiomeSurfaceMaterialData $surfaceMaterial,
		private bool $defaultOverworldSurface,
		private bool $swampSurface,
		private bool $frozenOceanSurface,
		private bool $theEndSurface,
		private ?BiomeMesaSurfaceData $mesaSurface,
		private ?BiomeCappedSurfaceData $cappedSurface,
		private ?BiomeNoiseGradientSurfaceData $noiseGradientSurface,
	){}

	public function getSurfaceMaterial() : ?BiomeSurfaceMaterialData{ return $this->surfaceMaterial; }

	public function hasDefaultOverworldSurface() : bool{ return $this->defaultOverworldSurface; }

	public function hasSwampSurface() : bool{ return $this->swampSurface; }

	public function hasFrozenOceanSurface() : bool{ return $this->frozenOceanSurface; }

	public function hasTheEndSurface() : bool{ return $this->theEndSurface; }

	public function getMesaSurface() : ?BiomeMesaSurfaceData{ return $this->mesaSurface; }

	public function getCappedSurface() : ?BiomeCappedSurfaceData{ return $this->cappedSurface; }

	public function getNoiseGradientSurface() : ?BiomeNoiseGradientSurfaceData{ return $this->noiseGradientSurface; }

	public static function read(ByteBufferReader $in) : self{
		$surfaceMaterial = CommonTypes::readOptional($in, fn() => BiomeSurfaceMaterialData::read($in));
		$defaultOverworldSurface = CommonTypes::getBool($in);
		$swampSurface = CommonTypes::getBool($in);
		$frozenOceanSurface = CommonTypes::getBool($in);
		$theEndSurface = CommonTypes::getBool($in);
		$mesaSurface = CommonTypes::readOptional($in, fn() => BiomeMesaSurfaceData::read($in));
		$cappedSurface = CommonTypes::readOptional($in, fn() => BiomeCappedSurfaceData::read($in));
		$noiseGradientSurface = CommonTypes::readOptional($in, fn() => BiomeNoiseGradientSurfaceData::read($in));

		return new self(
			$surfaceMaterial,
			$defaultOverworldSurface,
			$swampSurface,
			$frozenOceanSurface,
			$theEndSurface,
			$mesaSurface,
			$cappedSurface,
			$noiseGradientSurface
		);
	}

	public function write(ByteBufferWriter $out) : void{
		CommonTypes::writeOptional($out, $this->surfaceMaterial, fn(ByteBufferWriter $out, BiomeSurfaceMaterialData $v) => $v->write($out));
		CommonTypes::putBool($out, $this->defaultOverworldSurface);
		CommonTypes::putBool($out, $this->swampSurface);
		CommonTypes::putBool($out, $this->frozenOceanSurface);
		CommonTypes::putBool($out, $this->theEndSurface);
		CommonTypes::writeOptional($out, $this->mesaSurface, fn(ByteBufferWriter $out, BiomeMesaSurfaceData $v) => $v->write($out));
		CommonTypes::writeOptional($out, $this->cappedSurface, fn(ByteBufferWriter $out, BiomeCappedSurfaceData $v) => $v->write($out));
		CommonTypes::writeOptional($out, $this->noiseGradientSurface, fn(ByteBufferWriter $out, BiomeNoiseGradientSurfaceData $v) => $v->write($out));
	}
}
