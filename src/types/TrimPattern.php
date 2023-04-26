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

final class TrimPattern{

	public function __construct(
		private string $itemId,
		private string $patternId
	){}

	public function getItemId() : string{ return $this->itemId; }

	public function getPatternId() : string{ return $this->patternId; }

	public static function read(PacketSerializer $in) : self{
		$itemId = $in->getString();
		$patternId = $in->getString();
		return new self($itemId, $patternId);
	}

	public function write(PacketSerializer $out) : void{
		$out->putString($this->itemId);
		$out->putString($this->patternId);
	}
}
