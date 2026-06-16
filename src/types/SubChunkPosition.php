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

	public static function read(ByteBufferReader $in, bool $cereal = false) : self{
		if($cereal){
			$x = LE::readSignedInt($in);
			$y = LE::readSignedInt($in);
			$z = LE::readSignedInt($in);
		}else{
			$x = VarInt::readSignedInt($in);
			$y = VarInt::readSignedInt($in);
			$z = VarInt::readSignedInt($in);
		}

		return new self($x, $y, $z);
	}

	public function write(ByteBufferWriter $out, bool $cereal = false) : void{
		if($cereal){
			LE::writeSignedInt($out, $this->x);
			LE::writeSignedInt($out, $this->y);
			LE::writeSignedInt($out, $this->z);
		}else{
			VarInt::writeSignedInt($out, $this->x);
			VarInt::writeSignedInt($out, $this->y);
			VarInt::writeSignedInt($out, $this->z);
		}
	}
}
