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

use pmmp\encoding\Byte;
use pmmp\encoding\ByteBufferReader;
use pmmp\encoding\ByteBufferWriter;

final class Enchant{
	public function __construct(
		private int $id,
		private int $level
	){}

	public function getId() : int{ return $this->id; }

	public function getLevel() : int{ return $this->level; }

	public static function read(ByteBufferReader $in) : self{
		$id = Byte::readUnsigned($in);
		$level = Byte::readUnsigned($in);
		return new self($id, $level);
	}

	public function write(ByteBufferWriter $out) : void{
		Byte::writeUnsigned($out, $this->id);
		Byte::writeUnsigned($out, $this->level);
	}
}
