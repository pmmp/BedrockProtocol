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

final class EducationSettingsAgentCapabilities{
	public function __construct(
		private ?bool $canModifyBlocks
	){}

	public function getCanModifyBlocks() : ?bool{ return $this->canModifyBlocks; }

	public static function read(PacketSerializer $in) : self{
		$canModifyBlocks = $in->readOptional($in->getBool(...));
		return new self($canModifyBlocks);
	}

	public function write(PacketSerializer $out) : void{
		$out->writeOptional($this->canModifyBlocks, $out->putBool(...));
	}
}
