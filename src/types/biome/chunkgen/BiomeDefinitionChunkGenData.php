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

final class BiomeDefinitionChunkGenData{

	public function __construct(
		private ?BiomeClimateData $climate,
		private ?BiomeConsolidatedFeaturesData $consolidatedFeatures,
		private ?BiomeMountainParamsData $mountainParams,
		private ?BiomeSurfaceMaterialAdjustmentData $surfaceMaterialAdjustment,
		private ?BiomeSurfaceMaterialData $surfaceMaterial,
		private bool $swampSurface,
		private bool $frozenOceanSurface,
		private bool $theEndSurface,
		private ?BiomeMesaSurfaceData $mesaSurface,
		private ?BiomeCappedSurfaceData $cappedSurface,
		private ?BiomeOverworldGenRulesData $overworldGenRules,
		private ?BiomeMultinoiseGenRulesData $multinoiseGenRules,
		private ?BiomeLegacyWorldGenRulesData $legacyWorldGenRules,
	){}

	public function getClimate() : ?BiomeClimateData{ return $this->climate; }

	public function getConsolidatedFeatures() : ?BiomeConsolidatedFeaturesData{ return $this->consolidatedFeatures; }

	public function getMountainParams() : ?BiomeMountainParamsData{ return $this->mountainParams; }

	public function getSurfaceMaterialAdjustment() : ?BiomeSurfaceMaterialAdjustmentData{ return $this->surfaceMaterialAdjustment; }

	public function getSurfaceMaterial() : ?BiomeSurfaceMaterialData{ return $this->surfaceMaterial; }

	public function hasSwampSurface() : bool{ return $this->swampSurface; }

	public function hasFrozenOceanSurface() : bool{ return $this->frozenOceanSurface; }

	public function hasTheEndSurface() : bool{ return $this->theEndSurface; }

	public function getMesaSurface() : ?BiomeMesaSurfaceData{ return $this->mesaSurface; }

	public function getCappedSurface() : ?BiomeCappedSurfaceData{ return $this->cappedSurface; }

	public function getOverworldGenRules() : ?BiomeOverworldGenRulesData{ return $this->overworldGenRules; }

	public function getMultinoiseGenRules() : ?BiomeMultinoiseGenRulesData{ return $this->multinoiseGenRules; }

	public function getLegacyWorldGenRules() : ?BiomeLegacyWorldGenRulesData{ return $this->legacyWorldGenRules; }

	public static function read(ByteBufferReader $in) : self{
		$climate = CommonTypes::readOptional($in, fn() => BiomeClimateData::read($in));
		$consolidatedFeatures = CommonTypes::readOptional($in, fn() => BiomeConsolidatedFeaturesData::read($in));
		$mountainParams = CommonTypes::readOptional($in, fn() => BiomeMountainParamsData::read($in));
		$surfaceMaterialAdjustment = CommonTypes::readOptional($in, fn() => BiomeSurfaceMaterialAdjustmentData::read($in));
		$surfaceMaterial = CommonTypes::readOptional($in, fn() => BiomeSurfaceMaterialData::read($in));
		$swampSurface = CommonTypes::getBool($in);
		$frozenOceanSurface = CommonTypes::getBool($in);
		$theEndSurface = CommonTypes::getBool($in);
		$mesaSurface = CommonTypes::readOptional($in, fn() => BiomeMesaSurfaceData::read($in));
		$cappedSurface = CommonTypes::readOptional($in, fn() => BiomeCappedSurfaceData::read($in));
		$overworldGenRules = CommonTypes::readOptional($in, fn() => BiomeOverworldGenRulesData::read($in));
		$multinoiseGenRules = CommonTypes::readOptional($in, fn() => BiomeMultinoiseGenRulesData::read($in));
		$legacyWorldGenRules = CommonTypes::readOptional($in, fn() => BiomeLegacyWorldGenRulesData::read($in));

		return new self(
			$climate,
			$consolidatedFeatures,
			$mountainParams,
			$surfaceMaterialAdjustment,
			$surfaceMaterial,
			$swampSurface,
			$frozenOceanSurface,
			$theEndSurface,
			$mesaSurface,
			$cappedSurface,
			$overworldGenRules,
			$multinoiseGenRules,
			$legacyWorldGenRules
		);
	}

	public function write(ByteBufferWriter $out) : void{
		CommonTypes::writeOptional($out, $this->climate, fn(ByteBufferWriter $out, BiomeClimateData $v) => $v->write($out));
		CommonTypes::writeOptional($out, $this->consolidatedFeatures, fn(ByteBufferWriter $out, BiomeConsolidatedFeaturesData $v) => $v->write($out));
		CommonTypes::writeOptional($out, $this->mountainParams, fn(ByteBufferWriter $out, BiomeMountainParamsData $v) => $v->write($out));
		CommonTypes::writeOptional($out, $this->surfaceMaterialAdjustment, fn(ByteBufferWriter $out, BiomeSurfaceMaterialAdjustmentData $v) => $v->write($out));
		CommonTypes::writeOptional($out, $this->surfaceMaterial, fn(ByteBufferWriter $out, BiomeSurfaceMaterialData $v) => $v->write($out));
		CommonTypes::putBool($out, $this->swampSurface);
		CommonTypes::putBool($out, $this->frozenOceanSurface);
		CommonTypes::putBool($out, $this->theEndSurface);
		CommonTypes::writeOptional($out, $this->mesaSurface, fn(ByteBufferWriter $out, BiomeMesaSurfaceData $v) => $v->write($out));
		CommonTypes::writeOptional($out, $this->cappedSurface, fn(ByteBufferWriter $out, BiomeCappedSurfaceData $v) => $v->write($out));
		CommonTypes::writeOptional($out, $this->overworldGenRules, fn(ByteBufferWriter $out, BiomeOverworldGenRulesData $v) => $v->write($out));
		CommonTypes::writeOptional($out, $this->multinoiseGenRules, fn(ByteBufferWriter $out, BiomeMultinoiseGenRulesData $v) => $v->write($out));
		CommonTypes::writeOptional($out, $this->legacyWorldGenRules, fn(ByteBufferWriter $out, BiomeLegacyWorldGenRulesData $v) => $v->write($out));
	}
}
