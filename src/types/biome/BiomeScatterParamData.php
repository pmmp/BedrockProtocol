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

use pocketmine\network\mcpe\protocol\serializer\PacketSerializer;
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

	public static function read(PacketSerializer $in) : self{
		$coordinates = [];
		for($i = 0, $count = $in->getUnsignedVarInt(); $i < $count; ++$i){
			$coordinates[] = BiomeCoordinateData::read($in);
		}
		$evalOrder = $in->getVarInt();
		$chancePercentType = $in->getVarInt();
		$chancePercent = $in->getLShort();
		$chanceNumerator = $in->getLInt();
		$chanceDenominator = $in->getLInt();
		$iterationsType = $in->getVarInt();
		$iterations = $in->getLShort();

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

	public function write(PacketSerializer $out) : void{
		$out->putUnsignedVarInt(count($this->coordinates));
		foreach($this->coordinates as $coordinate){
			$coordinate->write($out);
		}
		$out->putVarInt($this->evalOrder);
		$out->putVarInt($this->chancePercentType);
		$out->putLShort($this->chancePercent);
		$out->putLInt($this->chanceNumerator);
		$out->putLInt($this->chanceDenominator);
		$out->putVarInt($this->iterationsType);
		$out->putLShort($this->iterations);
	}
}
