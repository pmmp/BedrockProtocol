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
use pmmp\encoding\VarInt;
use function count;

final class BiomeConsolidatedFeaturesData{

	/**
	 * @param BiomeConsolidatedFeatureData[] $features
	 */
	public function __construct(
		private array $features,
	){}

	/**
	 * @return BiomeConsolidatedFeatureData[]
	 */
	public function getFeatures() : array{ return $this->features; }

	public static function read(ByteBufferReader $in) : self{
		$features = [];

		for($i = 0, $count = VarInt::readUnsignedInt($in); $i < $count; ++$i){
			$features[] = BiomeConsolidatedFeatureData::read($in);
		}

		return new self($features);
	}

	public function write(ByteBufferWriter $out) : void{
		VarInt::writeUnsignedInt($out, count($this->features));
		foreach($this->features as $feature){
			$feature->write($out);
		}
	}
}
