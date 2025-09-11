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
use pmmp\encoding\LE;
use pocketmine\network\mcpe\protocol\types\GetTypeIdFromConstTrait;

final class FloatMetadataProperty implements MetadataProperty{
	use GetTypeIdFromConstTrait;

	public const ID = EntityMetadataTypes::FLOAT;

	public function __construct(
		private float $value
	){}

	public function getValue() : float{
		return $this->value;
	}

	public function equals(MetadataProperty $other) : bool{
		return $other instanceof self and $other->value === $this->value;
	}

	public static function read(ByteBufferReader $in) : self{
		return new self(LE::readFloat($in));
	}

	public function write(ByteBufferWriter $out) : void{
		LE::writeFloat($out, $this->value);
	}
}
