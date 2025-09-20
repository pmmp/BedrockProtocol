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
use function count;

final class BiomeConditionalTransformationData{

	/**
	 * @param BiomeWeightedData[] $weightedBiomes
	 */
	public function __construct(
		private array $weightedBiomes,
		private int $conditionJSON,
		private int $minPassingNeighbors,
	){}

	/**
	 * @return BiomeWeightedData[]
	 */
	public function getWeightedBiomes() : array{ return $this->weightedBiomes; }

	public function getConditionJSON() : int{ return $this->conditionJSON; }

	public function getMinPassingNeighbors() : int{ return $this->minPassingNeighbors; }

	public static function read(ByteBufferReader $in) : self{
		$weightedBiomes = [];
		for($i = 0, $count = VarInt::readUnsignedInt($in); $i < $count; ++$i){
			$weightedBiomes[] = BiomeWeightedData::read($in);
		}

		$conditionJSON = LE::readSignedShort($in);
		$minPassingNeighbors = LE::readUnsignedInt($in);

		return new self(
			$weightedBiomes,
			$conditionJSON,
			$minPassingNeighbors,
		);
	}

	public function write(ByteBufferWriter $out) : void{
		VarInt::writeUnsignedInt($out, count($this->weightedBiomes));
		foreach($this->weightedBiomes as $biome){
			$biome->write($out);
		}

		LE::writeSignedShort($out, $this->conditionJSON);
		LE::writeUnsignedInt($out, $this->minPassingNeighbors);
	}
}
