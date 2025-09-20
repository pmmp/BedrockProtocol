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

final class BiomeMesaSurfaceData{

	public function __construct(
		private int $clayMaterial,
		private int $hardClayMaterial,
		private bool $brycePillars,
		private bool $forest,
	){}

	public function getClayMaterial() : int{ return $this->clayMaterial; }

	public function getHardClayMaterial() : int{ return $this->hardClayMaterial; }

	public function hasBrycePillars() : bool{ return $this->brycePillars; }

	public function hasForest() : bool{ return $this->forest; }

	public static function read(ByteBufferReader $in) : self{
		$clayMaterial = LE::readUnsignedInt($in);
		$hardClayMaterial = LE::readUnsignedInt($in);
		$brycePillars = CommonTypes::getBool($in);
		$forest = CommonTypes::getBool($in);

		return new self(
			$clayMaterial,
			$hardClayMaterial,
			$brycePillars,
			$forest
		);
	}

	public function write(ByteBufferWriter $out) : void{
		LE::writeUnsignedInt($out, $this->clayMaterial);
		LE::writeUnsignedInt($out, $this->hardClayMaterial);
		CommonTypes::putBool($out, $this->brycePillars);
		CommonTypes::putBool($out, $this->forest);
	}
}
