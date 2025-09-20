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

	public static function read(ByteBufferReader $in) : self{
		$minValueType = VarInt::readSignedInt($in);
		$minValue = LE::readSignedShort($in);
		$maxValueType = VarInt::readSignedInt($in);
		$maxValue = LE::readSignedShort($in);
		$gridOffset = LE::readUnsignedInt($in);
		$gridStepSize = LE::readUnsignedInt($in);
		$distribution = VarInt::readSignedInt($in);

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

	public function write(ByteBufferWriter $out) : void{
		VarInt::writeSignedInt($out, $this->minValueType);
		LE::writeSignedShort($out, $this->minValue);
		VarInt::writeSignedInt($out, $this->maxValueType);
		LE::writeSignedShort($out, $this->maxValue);
		LE::writeUnsignedInt($out, $this->gridOffset);
		LE::writeUnsignedInt($out, $this->gridStepSize);
		VarInt::writeSignedInt($out, $this->distribution);
	}
}
