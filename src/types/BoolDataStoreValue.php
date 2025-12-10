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

final class BoolDataStoreValue extends DataStoreValue{
	public const ID = DataStoreValueType::BOOL;

	public function __construct(
		private readonly bool $value
	){}

	public function getValue() : bool{ return $this->value; }

	public function getTypeId() : int{
		return self::ID;
	}

	public function write(ByteBufferWriter $out) : void{
		CommonTypes::putBool($out, $this->value);
	}

	public static function read(ByteBufferReader $in) : self{
		return new self(CommonTypes::getBool($in));
	}
}
