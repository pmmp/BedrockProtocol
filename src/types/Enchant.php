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

final class Enchant{
	public function __construct(
		private int $id,
		private int $level
	){}

	public function getId() : int{ return $this->id; }

	public function getLevel() : int{ return $this->level; }

	public static function read(PacketSerializer $in) : self{
		$id = $in->getByte();
		$level = $in->getByte();
		return new self($id, $level);
	}

	public function write(PacketSerializer $out) : void{
		$out->putByte($this->id);
		$out->putByte($this->level);
	}
}
