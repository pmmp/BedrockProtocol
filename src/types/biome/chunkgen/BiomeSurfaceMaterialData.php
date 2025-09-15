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

final class BiomeSurfaceMaterialData{

	public function __construct(
		private int $topBlock,
		private int $midBlock,
		private int $seaFloorBlock,
		private int $foundationBlock,
		private int $seaBlock,
		private int $seaFloorDepth
	){}

	public function getTopBlock() : int{ return $this->topBlock; }

	public function getMidBlock() : int{ return $this->midBlock; }

	public function getSeaFloorBlock() : int{ return $this->seaFloorBlock; }

	public function getFoundationBlock() : int{ return $this->foundationBlock; }

	public function getSeaBlock() : int{ return $this->seaBlock; }

	public function getSeaFloorDepth() : int{ return $this->seaFloorDepth; }

	public static function read(ByteBufferReader $in) : self{
		$topBlock = LE::readUnsignedInt($in);
		$midBlock = LE::readUnsignedInt($in);
		$seaFloorBlock = LE::readUnsignedInt($in);
		$foundationBlock = LE::readUnsignedInt($in);
		$seaBlock = LE::readUnsignedInt($in);
		$seaFloorDepth = LE::readSignedInt($in);

		return new self(
			$topBlock,
			$midBlock,
			$seaFloorBlock,
			$foundationBlock,
			$seaBlock,
			$seaFloorDepth
		);
	}

	public function write(ByteBufferWriter $out) : void{
		LE::writeUnsignedInt($out, $this->topBlock);
		LE::writeUnsignedInt($out, $this->midBlock);
		LE::writeUnsignedInt($out, $this->seaFloorBlock);
		LE::writeUnsignedInt($out, $this->foundationBlock);
		LE::writeUnsignedInt($out, $this->seaBlock);
		LE::writeSignedInt($out, $this->seaFloorDepth);
	}
}
