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

use pocketmine\network\mcpe\protocol\serializer\PacketSerializer;
use function count;

final class BiomeSurfaceMaterialAdjustmentData{

	/**
	 * @param BiomeElementData[] $adjustments
	 */
	public function __construct(
		private array $adjustments,
	){}

	/**
	 * @return BiomeElementData[]
	 */
	public function getAdjustments() : array{ return $this->adjustments; }

	public static function read(PacketSerializer $in) : self{
		$adjustments = [];

		for($i = 0, $count = $in->getUnsignedVarInt(); $i < $count; ++$i){
			$adjustments[] = BiomeElementData::read($in);
		}

		return new self($adjustments);
	}

	public function write(PacketSerializer $out) : void{
		$out->putUnsignedVarInt(count($this->adjustments));
		foreach($this->adjustments as $adjustment){
			$adjustment->write($out);
		}
	}
}
