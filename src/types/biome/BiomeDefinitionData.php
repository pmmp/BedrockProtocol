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

namespace pocketmine\network\mcpe\protocol\types\biome;

use pmmp\encoding\ByteBufferReader;
use pmmp\encoding\ByteBufferWriter;
use pmmp\encoding\LE;
use pmmp\encoding\VarInt;
use pocketmine\color\Color;
use pocketmine\network\mcpe\protocol\serializer\CommonTypes;
use pocketmine\network\mcpe\protocol\types\biome\chunkgen\BiomeDefinitionChunkGenData;
use function count;

final class BiomeDefinitionData{

	/**
	 * @param int[]             $tagIndexes
	 * @phpstan-param list<int> $tagIndexes
	 */
	public function __construct(
		private int $nameIndex,
		private int $id,
		private float $temperature,
		private float $downfall,
		private float $foliageSnow,
		private float $depth,
		private float $scale,
		private Color $mapWaterColor,
		private bool $rain,
		private ?array $tagIndexes,
		private ?BiomeDefinitionChunkGenData $chunkGenData = null
	){}

	public function getNameIndex() : int{ return $this->nameIndex; }

	public function getId() : int{ return $this->id; }

	public function getTemperature() : float{ return $this->temperature; }

	public function getDownfall() : float{ return $this->downfall; }

	public function getFoliageSnow() : float{ return $this->foliageSnow; }

	public function getDepth() : float{ return $this->depth; }

	public function getScale() : float{ return $this->scale; }

	public function getMapWaterColor() : Color{ return $this->mapWaterColor; }

	public function hasRain() : bool{ return $this->rain; }

	/**
	 * @return int[]|null
	 * @phpstan-return list<int>|null
	 */
	public function getTagIndexes() : ?array{ return $this->tagIndexes; }

	public function getChunkGenData() : ?BiomeDefinitionChunkGenData{ return $this->chunkGenData; }

	public static function read(ByteBufferReader $in) : self{
		$nameIndex = LE::readUnsignedShort($in);
		$id = LE::readUnsignedShort($in);
		$temperature = LE::readFloat($in);
		$downfall = LE::readFloat($in);
		$foliageSnow = LE::readFloat($in);
		$depth = LE::readFloat($in);
		$scale = LE::readFloat($in);
		$mapWaterColor = Color::fromARGB(LE::readUnsignedInt($in));
		$rain = CommonTypes::getBool($in);
		$tags = CommonTypes::readOptional($in, function() use ($in) : array{
			$tagIndexes = [];

			for($i = 0, $count = VarInt::readUnsignedInt($in); $i < $count; ++$i){
				$tagIndexes[] = LE::readUnsignedShort($in);
			}

			return $tagIndexes;
		});
		$chunkGenData = CommonTypes::readOptional($in, fn() => BiomeDefinitionChunkGenData::read($in));

		return new self(
			$nameIndex,
			$id,
			$temperature,
			$downfall,
			$foliageSnow,
			$depth,
			$scale,
			$mapWaterColor,
			$rain,
			$tags,
			$chunkGenData
		);
	}

	public function write(ByteBufferWriter $out) : void{
		LE::writeUnsignedShort($out, $this->nameIndex);
		LE::writeUnsignedShort($out, $this->id);
		LE::writeFloat($out, $this->temperature);
		LE::writeFloat($out, $this->downfall);
		LE::writeFloat($out, $this->foliageSnow);
		LE::writeFloat($out, $this->depth);
		LE::writeFloat($out, $this->scale);
		LE::writeUnsignedInt($out, $this->mapWaterColor->toARGB());
		CommonTypes::putBool($out, $this->rain);
		CommonTypes::writeOptional($out, $this->tagIndexes, function(ByteBufferWriter $out, array $tagIndexes) : void{
			VarInt::writeUnsignedInt($out, count($tagIndexes));
			foreach($tagIndexes as $tag){
				LE::writeUnsignedShort($out, $tag);
			}
		});
		CommonTypes::writeOptional($out, $this->chunkGenData, fn(ByteBufferWriter $out, BiomeDefinitionChunkGenData $v) => $v->write($out));
	}
}
