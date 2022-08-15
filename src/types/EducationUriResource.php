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

final class EducationUriResource{
	public function __construct(
		private string $buttonName,
		private string $linkUri
	){}

	public function getButtonName() : string{ return $this->buttonName; }

	public function getLinkUri() : string{ return $this->linkUri; }

	public static function read(PacketSerializer $in) : self{
		$buttonName = $in->getString();
		$linkUri = $in->getString();
		return new self($buttonName, $linkUri);
	}

	public function write(PacketSerializer $out) : void{
		$out->putString($this->buttonName);
		$out->putString($this->linkUri);
	}
}
