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

namespace pocketmine\network\mcpe\protocol\types\entity;

use pocketmine\network\mcpe\protocol\serializer\PacketSerializer;
use function count;

final class PropertySyncData{
	/**
	 * @param int[] $intProperties
	 * @param float[] $floatProperties
	 * @phpstan-param array<int, int> $intProperties
	 * @phpstan-param array<int, float> $floatProperties
	 */
	public function __construct(
		private array $intProperties,
		private array $floatProperties,
	){}

	/**
	 * @return int[]
	 * @phpstan-return array<int, int>
	 */
	public function getIntProperties() : array{
		return $this->intProperties;
	}

	/**
	 * @return float[]
	 * @phpstan-return array<int, float>
	 */
	public function getFloatProperties() : array{
		return $this->floatProperties;
	}

	public static function read(PacketSerializer $in) : self{
		$intProperties = [];
		$floatProperties = [];

		for($i = 0, $count = $in->getUnsignedVarInt(); $i < $count; ++$i){
			$intProperties[$in->getUnsignedVarInt()] = $in->getVarInt();
		}
		for($i = 0, $count = $in->getUnsignedVarInt(); $i < $count; ++$i){
			$floatProperties[$in->getUnsignedVarInt()] = $in->getLFloat();
		}

		return new self($intProperties, $floatProperties);
	}

	public function write(PacketSerializer $out) : void{
		$out->putUnsignedVarInt(count($this->intProperties));
		foreach($this->intProperties as $key => $value){
			$out->putUnsignedVarInt($key);
			$out->putVarInt($value);
		}
		$out->putUnsignedVarInt(count($this->floatProperties));
		foreach($this->floatProperties as $key => $value){
			$out->putUnsignedVarInt($key);
			$out->putLFloat($value);
		}
	}
}
