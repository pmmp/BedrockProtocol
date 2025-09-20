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
use pocketmine\network\mcpe\protocol\serializer\CommonTypes;
use function count;

final class BiomeCappedSurfaceData{

	/**
	 * @param int[] $floorBlocks
	 * @param int[] $ceilingBlocks
	 */
	public function __construct(
		private array $floorBlocks,
		private array $ceilingBlocks,
		private ?int $seaBlock,
		private ?int $foundationBlock,
		private ?int $beachBlock,
	){}

	/**
	 * @return int[]
	 */
	public function getFloorBlocks() : array{ return $this->floorBlocks; }

	/**
	 * @return int[]
	 */
	public function getCeilingBlocks() : array{ return $this->ceilingBlocks; }

	public function getSeaBlock() : ?int{ return $this->seaBlock; }

	public function getFoundationBlock() : ?int{ return $this->foundationBlock; }

	public function getBeachBlock() : ?int{ return $this->beachBlock; }

	public static function read(ByteBufferReader $in) : self{
		$floorBlocks = [];
		for($i = 0, $count = VarInt::readUnsignedInt($in); $i < $count; ++$i){
			$floorBlocks[] = LE::readUnsignedInt($in);
		}

		$ceilingBlocks = [];
		for($i = 0, $count = VarInt::readUnsignedInt($in); $i < $count; ++$i){
			$ceilingBlocks[] = LE::readUnsignedInt($in);
		}

		$seaBlock = CommonTypes::readOptional($in, LE::readUnsignedInt(...));
		$foundationBlock = CommonTypes::readOptional($in, LE::readUnsignedInt(...));
		$beachBlock = CommonTypes::readOptional($in, LE::readUnsignedInt(...));

		return new self(
			$floorBlocks,
			$ceilingBlocks,
			$seaBlock,
			$foundationBlock,
			$beachBlock
		);
	}

	public function write(ByteBufferWriter $out) : void{
		VarInt::writeUnsignedInt($out, count($this->floorBlocks));
		foreach($this->floorBlocks as $block){
			LE::writeUnsignedInt($out, $block);
		}

		VarInt::writeUnsignedInt($out, count($this->ceilingBlocks));
		foreach($this->ceilingBlocks as $block){
			LE::writeUnsignedInt($out, $block);
		}

		CommonTypes::writeOptional($out, $this->seaBlock, LE::writeUnsignedInt(...));
		CommonTypes::writeOptional($out, $this->foundationBlock, LE::writeUnsignedInt(...));
		CommonTypes::writeOptional($out, $this->beachBlock, LE::writeUnsignedInt(...));
	}
}
