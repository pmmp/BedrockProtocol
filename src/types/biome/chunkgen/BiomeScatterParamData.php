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

final class BiomeScatterParamData{

	/**
	 * @param BiomeCoordinateData[] $coordinates
	 */
	public function __construct(
		private array $coordinates,
		private int $evalOrder,
		private int $chancePercentType,
		private int $chancePercent,
		private int $chanceNumerator,
		private int $chanceDenominator,
		private int $iterationsType,
		private int $iterations,
	){}

	/**
	 * @return BiomeCoordinateData[]
	 */
	public function getCoordinates() : array{ return $this->coordinates; }

	public function getEvalOrder() : int{ return $this->evalOrder; }

	public function getChancePercentType() : int{ return $this->chancePercentType; }

	public function getChancePercent() : int{ return $this->chancePercent; }

	public function getChanceNumerator() : int{ return $this->chanceNumerator; }

	public function getChanceDenominator() : int{ return $this->chanceDenominator; }

	public function getIterationsType() : int{ return $this->iterationsType; }

	public function getIterations() : int{ return $this->iterations; }

	public static function read(ByteBufferReader $in) : self{
		$coordinates = [];
		for($i = 0, $count = VarInt::readUnsignedInt($in); $i < $count; ++$i){
			$coordinates[] = BiomeCoordinateData::read($in);
		}
		$evalOrder = VarInt::readSignedInt($in);
		$chancePercentType = VarInt::readSignedInt($in);
		$chancePercent = LE::readSignedShort($in);
		$chanceNumerator = LE::readSignedInt($in);
		$chanceDenominator = LE::readSignedInt($in);
		$iterationsType = VarInt::readSignedInt($in);
		$iterations = LE::readSignedShort($in);

		return new self(
			$coordinates,
			$evalOrder,
			$chancePercentType,
			$chancePercent,
			$chanceNumerator,
			$chanceDenominator,
			$iterationsType,
			$iterations
		);
	}

	public function write(ByteBufferWriter $out) : void{
		VarInt::writeUnsignedInt($out, count($this->coordinates));
		foreach($this->coordinates as $coordinate){
			$coordinate->write($out);
		}
		VarInt::writeSignedInt($out, $this->evalOrder);
		VarInt::writeSignedInt($out, $this->chancePercentType);
		LE::writeSignedShort($out, $this->chancePercent);
		LE::writeSignedInt($out, $this->chanceNumerator);
		LE::writeSignedInt($out, $this->chanceDenominator);
		VarInt::writeSignedInt($out, $this->iterationsType);
		LE::writeSignedShort($out, $this->iterations);
	}
}
