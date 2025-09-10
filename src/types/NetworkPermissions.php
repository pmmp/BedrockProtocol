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

final class NetworkPermissions{
	public function __construct(
		private bool $disableClientSounds
	){}

	public function disableClientSounds() : bool{
		return $this->disableClientSounds;
	}

	public static function decode(ByteBufferReader $in) : self{
		$disableClientSounds = CommonTypes::getBool($in);
		return new self($disableClientSounds);
	}

	public function encode(ByteBufferWriter $out) : void{
		CommonTypes::putBool($out, $this->disableClientSounds);
	}
}
