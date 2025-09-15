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

final class BiomeWeightedTemperatureData{

	public function __construct(
		private int $temperature,
		private int $weight,
	){}

	public function getTemperature() : int{ return $this->temperature; }

	public function getWeight() : int{ return $this->weight; }

	public static function read(ByteBufferReader $in) : self{
		$temperature = VarInt::readSignedInt($in);
		$weight = LE::readUnsignedInt($in);

		return new self(
			$temperature,
			$weight
		);
	}

	public function write(ByteBufferWriter $out) : void{
		VarInt::writeSignedInt($out, $this->temperature);
		LE::writeUnsignedInt($out, $this->weight);
	}
}
