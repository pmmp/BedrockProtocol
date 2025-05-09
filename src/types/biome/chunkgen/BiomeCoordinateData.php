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

use pocketmine\network\mcpe\protocol\serializer\PacketSerializer;

final class BiomeCoordinateData{

	public function __construct(
		private int $minValueType,
		private int $minValue,
		private int $maxValueType,
		private int $maxValue,
		private int $gridOffset,
		private int $gridStepSize,
		private int $distribution
	){}

	public function getMinValueType() : int{ return $this->minValueType; }

	public function getMinValue() : int{ return $this->minValue; }

	public function getMaxValueType() : int{ return $this->maxValueType; }

	public function getMaxValue() : int{ return $this->maxValue; }

	public function getGridOffset() : int{ return $this->gridOffset; }

	public function getGridStepSize() : int{ return $this->gridStepSize; }

	public function getDistribution() : int{ return $this->distribution; }

	public static function read(PacketSerializer $in) : self{
		$minValueType = $in->getVarInt();
		$minValue = $in->getLShort();
		$maxValueType = $in->getVarInt();
		$maxValue = $in->getLShort();
		$gridOffset = $in->getLInt();
		$gridStepSize = $in->getLInt();
		$distribution = $in->getVarInt();

		return new self(
			$minValueType,
			$minValue,
			$maxValueType,
			$maxValue,
			$gridOffset,
			$gridStepSize,
			$distribution
		);
	}

	public function write(PacketSerializer $out) : void{

	}
}
