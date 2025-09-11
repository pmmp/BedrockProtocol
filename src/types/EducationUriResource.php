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
use pocketmine\network\mcpe\protocol\serializer\CommonTypes;

final class EducationUriResource{
	public function __construct(
		private string $buttonName,
		private string $linkUri
	){}

	public function getButtonName() : string{ return $this->buttonName; }

	public function getLinkUri() : string{ return $this->linkUri; }

	public static function read(ByteBufferReader $in) : self{
		$buttonName = CommonTypes::getString($in);
		$linkUri = CommonTypes::getString($in);
		return new self($buttonName, $linkUri);
	}

	public function write(ByteBufferWriter $out) : void{
		CommonTypes::putString($out, $this->buttonName);
		CommonTypes::putString($out, $this->linkUri);
	}
}
