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

final class EducationSettingsExternalLinkSettings{
	public function __construct(
		private string $url,
		private string $displayName
	){}

	public function getUrl() : string{ return $this->url; }

	public function getDisplayName() : string{ return $this->displayName; }

	public static function read(PacketSerializer $in) : self{
		$url = $in->getString();
		$displayName = $in->getString();
		return new self($displayName, $url);
	}

	public function write(PacketSerializer $out) : void{
		$out->putString($this->url);
		$out->putString($this->displayName);
	}
}
