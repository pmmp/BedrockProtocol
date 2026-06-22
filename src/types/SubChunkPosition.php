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

use pmmp\encoding\ByteBufferReader;
use pmmp\encoding\ByteBufferWriter;
use pmmp\encoding\LE;
use pmmp\encoding\VarInt;

final class SubChunkPosition{

	public function __construct(
		private int $x,
		private int $y,
		private int $z,
	){}

	public function getX() : int{ return $this->x; }

	public function getY() : int{ return $this->y; }

	public function getZ() : int{ return $this->z; }

	public static function readFixedInts(ByteBufferReader $in) : self{
		$x = LE::readSignedInt($in);
		$y = LE::readSignedInt($in);
		$z = LE::readSignedInt($in);

		return new self($x, $y, $z);
	}

	public static function readVarInts(ByteBufferReader $in) : self{
		$x = VarInt::readSignedInt($in);
		$y = VarInt::readSignedInt($in);
		$z = VarInt::readSignedInt($in);

		return new self($x, $y, $z);
	}

	public function writeFixedInts(ByteBufferWriter $out) : void{
		LE::writeSignedInt($out, $this->x);
		LE::writeSignedInt($out, $this->y);
		LE::writeSignedInt($out, $this->z);
	}

	public function writeVarInts(ByteBufferWriter $out) : void{
		VarInt::writeSignedInt($out, $this->x);
		VarInt::writeSignedInt($out, $this->y);
		VarInt::writeSignedInt($out, $this->z);
	}
}
