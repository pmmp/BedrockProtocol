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
use pocketmine\network\mcpe\protocol\serializer\CommonTypes;

final class BiomeNoiseBlockSpecifier{

	public function __construct(
		private string $noise,
		private float $threshold,
		private float $min,
		private float $max,
		private int $block,
	){}

	public function getNoise() : string{ return $this->noise; }

	public function getThreshold() : float{ return $this->threshold; }

	public function getMin() : float{ return $this->min; }

	public function getMax() : float{ return $this->max; }

	public function getBlock() : int{ return $this->block; }

	public static function read(ByteBufferReader $in) : self{
		$noise = CommonTypes::getString($in);
		$threshold = LE::readFloat($in);
		$min = LE::readFloat($in);
		$max = LE::readFloat($in);
		$block = LE::readUnsignedInt($in);

		return new self(
			$noise,
			$threshold,
			$min,
			$max,
			$block
		);
	}

	public function write(ByteBufferWriter $out) : void{
		CommonTypes::putString($out, $this->noise);
		LE::writeFloat($out, $this->threshold);
		LE::writeFloat($out, $this->min);
		LE::writeFloat($out, $this->max);
		LE::writeUnsignedInt($out, $this->block);
	}
}
