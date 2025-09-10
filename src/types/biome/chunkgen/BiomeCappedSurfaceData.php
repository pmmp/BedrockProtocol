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
			$floorBlocks[] = /* TODO: check if this should be unsigned */ LE::readSignedInt($in);
		}

		$ceilingBlocks = [];
		for($i = 0, $count = VarInt::readUnsignedInt($in); $i < $count; ++$i){
			$ceilingBlocks[] = /* TODO: check if this should be unsigned */ LE::readSignedInt($in);
		}

		$seaBlock = CommonTypes::readOptional($in, /* TODO: check if this should be unsigned */ LE::readSignedInt(...));
		$foundationBlock = CommonTypes::readOptional($in, /* TODO: check if this should be unsigned */ LE::readSignedInt(...));
		$beachBlock = CommonTypes::readOptional($in, /* TODO: check if this should be unsigned */ LE::readSignedInt(...));

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
			/* TODO: check if this should be unsigned */ LE::writeSignedInt($out, $block);
		}

		VarInt::writeUnsignedInt($out, count($this->ceilingBlocks));
		foreach($this->ceilingBlocks as $block){
			/* TODO: check if this should be unsigned */ LE::writeSignedInt($out, $block);
		}

		CommonTypes::writeOptional($out, $this->seaBlock, /* TODO: check if this should be unsigned */ LE::writeSignedInt(...));
		CommonTypes::writeOptional($out, $this->foundationBlock, /* TODO: check if this should be unsigned */ LE::writeSignedInt(...));
		CommonTypes::writeOptional($out, $this->beachBlock, /* TODO: check if this should be unsigned */ LE::writeSignedInt(...));
	}
}
