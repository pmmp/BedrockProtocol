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

namespace pocketmine\network\mcpe\protocol\types\entity;

use pmmp\encoding\ByteBufferReader;
use pmmp\encoding\ByteBufferWriter;
use pmmp\encoding\VarInt;
use pocketmine\network\mcpe\protocol\types\GetTypeIdFromConstTrait;
use const PHP_INT_MAX;
use const PHP_INT_MIN;

final class LongMetadataProperty implements MetadataProperty{
	use GetTypeIdFromConstTrait;
	use IntegerishMetadataProperty;

	public const ID = EntityMetadataTypes::LONG;

	protected function min() : int{
		return PHP_INT_MIN;
	}

	protected function max() : int{
		return PHP_INT_MAX;
	}

	public static function read(ByteBufferReader $in) : self{
		return new self(VarInt::readSignedLong($in));
	}

	public function write(ByteBufferWriter $out) : void{
		VarInt::writeSignedLong($out, $this->value);
	}
}
