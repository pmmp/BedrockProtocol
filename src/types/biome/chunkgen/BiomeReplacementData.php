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

final class BiomeReplacementData{

	/**
	 * @param int[] $targetBiomes
	 */
	public function __construct(
		private int $biome,
		private int $dimension,
		private array $targetBiomes,
		private float $amount,
		private int $replacementIndex
	){}

	public function getBiome() : int{ return $this->biome; }

	public function getDimension() : int{ return $this->dimension; }

	/**
	 * @return int[]
	 */
	public function getTargetBiomes() : array{ return $this->targetBiomes; }

	public function getAmount() : float{ return $this->amount; }

	public function getReplacementIndex() : int{ return $this->replacementIndex; }

	public static function read(ByteBufferReader $in) : self{
		$biome = LE::readSignedShort($in);
		$dimension = VarInt::readSignedInt($in);
		$targetBiomes = [];
		$targetBiomeCount = VarInt::readUnsignedInt($in);
		for($i = 0; $i < $targetBiomeCount; ++$i){
			$targetBiomes[] = LE::readSignedShort($in);
		}
		$amount = LE::readFloat($in);
		$replacementIndex = LE::readUnsignedInt($in);
		return new self($biome, $dimension, $targetBiomes, $amount, $replacementIndex);
	}

	public function write(ByteBufferWriter $out) : void{
		LE::writeSignedShort($out, $this->biome);
		VarInt::writeSignedInt($out, $this->dimension);
		VarInt::writeUnsignedInt($out, count($this->targetBiomes));
		foreach($this->targetBiomes as $biome){
			LE::writeSignedShort($out, $biome);
		}
		LE::writeFloat($out, $this->amount);
		LE::writeUnsignedInt($out, $this->replacementIndex);
	}
}
