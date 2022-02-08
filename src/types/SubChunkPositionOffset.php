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

namespace pocketmine\network\mcpe\protocol\types;

use pocketmine\network\mcpe\protocol\serializer\PacketSerializer;
use pocketmine\utils\Binary;
use pocketmine\utils\Limits;

final class SubChunkPositionOffset{

	public function __construct(
		private int $xOffset,
		private int $yOffset,
		private int $zOffset,
	){
		self::clampOffset($this->xOffset);
		self::clampOffset($this->yOffset);
		self::clampOffset($this->zOffset);
	}

	private static function clampOffset(int $v) : void{
		if($v < Limits::INT8_MIN || $v > Limits::INT8_MAX){
			throw new \InvalidArgumentException("Offsets must be within the range of a byte (" . Limits::INT8_MIN . " ... " . Limits::INT8_MAX . ")");
		}
	}

	public function getXOffset() : int{ return $this->xOffset; }

	public function getYOffset() : int{ return $this->yOffset; }

	public function getZOffset() : int{ return $this->zOffset; }

	public static function read(PacketSerializer $in) : self{
		$xOffset = Binary::signByte($in->getByte());
		$yOffset = Binary::signByte($in->getByte());
		$zOffset = Binary::signByte($in->getByte());

		return new self($xOffset, $yOffset, $zOffset);
	}

	public function write(PacketSerializer $out) : void{
		$out->putByte($this->xOffset);
		$out->putByte($this->yOffset);
		$out->putByte($this->zOffset);
	}
}
