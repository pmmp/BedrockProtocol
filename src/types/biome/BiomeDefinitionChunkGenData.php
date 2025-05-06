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

	public static function read(PacketSerializer $in) : self{
		$climate = $in->readOptional(fn() => BiomeClimateData::read($in));
		$consolidatedFeatures = $in->readOptional(fn() => BiomeConsolidatedFeaturesData::read($in));
		$mountainParams = $in->readOptional(fn() => BiomeMountainParamsData::read($in));
		$surfaceMaterialAdjustment = $in->readOptional(fn() => BiomeSurfaceMaterialAdjustmentData::read($in));
		$surfaceMaterial = $in->readOptional(fn() => BiomeSurfaceMaterialData::read($in));
		$swampSurface = $in->getBool();
		$frozenOceanSurface = $in->getBool();
		$theEndSurface = $in->getBool();
		$mesaSurface = $in->readOptional(fn() => BiomeMesaSurfaceData::read($in));
		$cappedSurface = $in->readOptional(fn() => BiomeCappedSurfaceData::read($in));
		$overworldGenRules = $in->readOptional(fn() => BiomeOverworldGenRulesData::read($in));
		$multinoiseGenRules = $in->readOptional(fn() => BiomeMultinoiseGenRulesData::read($in));
		$legacyWorldGenRules = $in->readOptional(fn() => BiomeLegacyWorldGenRulesData::read($in));

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

	public function write(PacketSerializer $out) : void{
		$out->writeOptional($this->climate, fn(BiomeClimateData $climate) => $climate->write($out));
		$out->writeOptional($this->consolidatedFeatures, fn(BiomeConsolidatedFeaturesData $consolidatedFeatures) => $consolidatedFeatures->write($out));
		$out->writeOptional($this->mountainParams, fn(BiomeMountainParamsData $mountainParams) => $mountainParams->write($out));
		$out->writeOptional($this->surfaceMaterialAdjustment, fn(BiomeSurfaceMaterialAdjustmentData $surfaceMaterialAdjustment) => $surfaceMaterialAdjustment->write($out));
		$out->writeOptional($this->surfaceMaterial, fn(BiomeSurfaceMaterialData $surfaceMaterial) => $surfaceMaterial->write($out));
		$out->putBool($this->swampSurface);
		$out->putBool($this->frozenOceanSurface);
		$out->putBool($this->theEndSurface);
		$out->writeOptional($this->mesaSurface, fn(BiomeMesaSurfaceData $mesaSurface) => $mesaSurface->write($out));
		$out->writeOptional($this->cappedSurface, fn(BiomeCappedSurfaceData $cappedSurface) => $cappedSurface->write($out));
		$out->writeOptional($this->overworldGenRules, fn(BiomeOverworldGenRulesData $overworldGenRules) => $overworldGenRules->write($out));
		$out->writeOptional($this->multinoiseGenRules, fn(BiomeMultinoiseGenRulesData $multinoiseGenRules) => $multinoiseGenRules->write($out));
		$out->writeOptional($this->legacyWorldGenRules, fn(BiomeLegacyWorldGenRulesData $legacyWorldGenRules) => $legacyWorldGenRules->write($out));
	}
}
