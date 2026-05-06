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

final class BiomeNoiseGradientSurfaceData{

	/**
	 * @param int[]   $nonReplaceableBlocks
	 * @param int[]   $gradientBlocks
	 * @param float[] $amplitudes
	 */
	public function __construct(
		private array $nonReplaceableBlocks,
		private array $gradientBlocks,
		private string $noiseSeed,
		private int $firstOctave,
		private array $amplitudes
	){}

	/**
	 * @return int[]
	 */
	public function getNonReplaceableBlocks() : array{ return $this->nonReplaceableBlocks; }

	/**
	 * @return int[]
	 */
	public function getGradientBlocks() : array{ return $this->gradientBlocks; }

	public function getNoiseSeed() : string{ return $this->noiseSeed; }

	public function getFirstOctave() : int{ return $this->firstOctave; }

	/**
	 * @return float[]
	 */
	public function getAmplitudes() : array{ return $this->amplitudes; }

	public static function read(ByteBufferReader $in) : self{
		$nonReplaceableBlocks = [];
		for($i = 0, $count = VarInt::readUnsignedInt($in); $i < $count; ++$i){
			$nonReplaceableBlocks[] = LE::readUnsignedInt($in);
		}

		$gradientBlocks = [];
		for($i = 0, $count = VarInt::readUnsignedInt($in); $i < $count; ++$i){
			$gradientBlocks[] = LE::readUnsignedInt($in);
		}

		$noiseSeed = CommonTypes::getString($in);
		$firstOctave = LE::readUnsignedInt($in);

		$amplitudes = [];
		for($i = 0, $count = VarInt::readUnsignedInt($in); $i < $count; ++$i){
			$amplitudes[] = LE::readFloat($in);
		}

		return new self(
			$nonReplaceableBlocks,
			$gradientBlocks,
			$noiseSeed,
			$firstOctave,
			$amplitudes
		);
	}

	public function write(ByteBufferWriter $out) : void{
		VarInt::writeUnsignedInt($out, count($this->nonReplaceableBlocks));
		foreach($this->nonReplaceableBlocks as $value){
			LE::writeUnsignedInt($out, $value);
		}

		VarInt::writeUnsignedInt($out, count($this->gradientBlocks));
		foreach($this->gradientBlocks as $value){
			LE::writeUnsignedInt($out, $value);
		}

		CommonTypes::putString($out, $this->noiseSeed);
		LE::writeUnsignedInt($out, $this->firstOctave);

		VarInt::writeUnsignedInt($out, count($this->amplitudes));
		foreach($this->amplitudes as $value){
			LE::writeFloat($out, $value);
		}
	}
}
