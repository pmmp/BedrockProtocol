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

final class BiomeMountainParamsData{

	public function __construct(
		private int $steepBlock,
		private bool $northSlopes,
		private bool $southSlopes,
		private bool $westSlopes,
		private bool $eastSlopes,
		private bool $topSlideEnabled,
	){}

	public function getSteepBlock() : int{ return $this->steepBlock; }

	public function hasNorthSlopes() : bool{ return $this->northSlopes; }

	public function hasSouthSlopes() : bool{ return $this->southSlopes; }

	public function hasWestSlopes() : bool{ return $this->westSlopes; }

	public function hasEastSlopes() : bool{ return $this->eastSlopes; }

	public function hasTopSlideEnabled() : bool{ return $this->topSlideEnabled; }

	public static function read(ByteBufferReader $in) : self{
		$steepBlock = LE::readUnsignedInt($in);
		$northSlopes = CommonTypes::getBool($in);
		$southSlopes = CommonTypes::getBool($in);
		$westSlopes = CommonTypes::getBool($in);
		$eastSlopes = CommonTypes::getBool($in);
		$topSlideEnabled = CommonTypes::getBool($in);

		return new self(
			$steepBlock,
			$northSlopes,
			$southSlopes,
			$westSlopes,
			$eastSlopes,
			$topSlideEnabled
		);
	}

	public function write(ByteBufferWriter $out) : void{
		LE::writeUnsignedInt($out, $this->steepBlock);
		CommonTypes::putBool($out, $this->northSlopes);
		CommonTypes::putBool($out, $this->southSlopes);
		CommonTypes::putBool($out, $this->westSlopes);
		CommonTypes::putBool($out, $this->eastSlopes);
		CommonTypes::putBool($out, $this->topSlideEnabled);
	}
}
