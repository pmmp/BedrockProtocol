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

namespace pocketmine\network\mcpe\protocol\types\ddui\update;

use pmmp\encoding\ByteBufferReader;
use pmmp\encoding\ByteBufferWriter;
use pmmp\encoding\LE;
use pocketmine\network\mcpe\protocol\types\GetTypeIdFromConstTrait;

final class DoubleDataStoreUpdateValue extends DataStoreUpdateValue{
	use GetTypeIdFromConstTrait;

	public const ID = DataStoreUpdateValueType::DOUBLE;

	public function __construct(
		private readonly float $value
	){}

	public function getValue() : float{ return $this->value; }

	public function write(ByteBufferWriter $out) : void{
		LE::writeDouble($out, $this->value);
	}

	public static function read(ByteBufferReader $in) : self{
		return new self(LE::readDouble($in));
	}
}
