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

namespace pocketmine\network\mcpe\protocol\types\cereal;

use pmmp\encoding\ByteBufferReader;
use pmmp\encoding\ByteBufferWriter;
use pocketmine\network\mcpe\protocol\serializer\CommonTypes;
use pocketmine\network\mcpe\protocol\types\GetTypeIdFromConstTrait;

final class DynamicValueString extends DynamicValue{
	use GetTypeIdFromConstTrait;

	public const ID = DynamicValueType::STRING;

	public function __construct(
		private string $value
	){}

	protected static function readValue(ByteBufferReader $in) : self{
		return new self(CommonTypes::getString($in));
	}

	protected function writeValue(ByteBufferWriter $out) : void{
		CommonTypes::putString($out, $this->value);
	}
}
