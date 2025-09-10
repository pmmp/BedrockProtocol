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
use pmmp\encoding\VarInt;

final class ChunkPosition{

	public function __construct(
		private int $x,
		private int $z
	){}

	public function getX() : int{ return $this->x; }

	public function getZ() : int{ return $this->z; }

	public static function read(ByteBufferReader $in) : self{
		$x = VarInt::readSignedInt($in);
		$z = VarInt::readSignedInt($in);

		return new self($x, $z);
	}

	public function write(ByteBufferWriter $out) : void{
		VarInt::writeSignedInt($out, $this->x);
		VarInt::writeSignedInt($out, $this->z);
	}
}
