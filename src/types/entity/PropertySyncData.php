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

use pmmp\encoding\ByteBufferReader;
use pmmp\encoding\ByteBufferWriter;
use pmmp\encoding\LE;
use pmmp\encoding\VarInt;
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

	public static function read(ByteBufferReader $in) : self{
		$intProperties = [];
		$floatProperties = [];

		for($i = 0, $count = VarInt::readUnsignedInt($in); $i < $count; ++$i){
			$intProperties[VarInt::readUnsignedInt($in)] = VarInt::readSignedInt($in);
		}
		for($i = 0, $count = VarInt::readUnsignedInt($in); $i < $count; ++$i){
			$floatProperties[VarInt::readUnsignedInt($in)] = LE::readFloat($in);
		}

		return new self($intProperties, $floatProperties);
	}

	public function write(ByteBufferWriter $out) : void{
		VarInt::writeUnsignedInt($out, count($this->intProperties));
		foreach($this->intProperties as $key => $value){
			VarInt::writeUnsignedInt($out, $key);
			VarInt::writeSignedInt($out, $value);
		}
		VarInt::writeUnsignedInt($out, count($this->floatProperties));
		foreach($this->floatProperties as $key => $value){
			VarInt::writeUnsignedInt($out, $key);
			LE::writeFloat($out, $value);
		}
	}
}
