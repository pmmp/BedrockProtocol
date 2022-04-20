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

final class DimensionData{

	public function __construct(
		private int $maxHeight,
		private int $minHeight,
		private int $generator
	){}

	public function getMaxHeight() : int{ return $this->maxHeight; }

	public function getMinHeight() : int{ return $this->minHeight; }

	public function getGenerator() : int{ return $this->generator; }

	public static function read(PacketSerializer $in) : self{
		$maxHeight = $in->getVarInt();
		$minHeight = $in->getVarInt();
		$generator = $in->getVarInt();

		return new self($maxHeight, $minHeight, $generator);
	}

	public function write(PacketSerializer $out) : void{
		$out->putVarInt($this->maxHeight);
		$out->putVarInt($this->minHeight);
		$out->putVarInt($this->generator);
	}
}
