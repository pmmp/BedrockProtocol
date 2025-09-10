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

final class BoolGameRule extends GameRule{
	use GetTypeIdFromConstTrait;

	public const ID = GameRuleType::BOOL;

	private bool $value;

	public function __construct(bool $value, bool $isPlayerModifiable){
		parent::__construct($isPlayerModifiable);
		$this->value = $value;
	}

	public function getValue() : bool{
		return $this->value;
	}

	public function encode(ByteBufferWriter $out) : void{
		CommonTypes::putBool($out, $this->value);
	}

	public static function decode(ByteBufferReader $in, bool $isPlayerModifiable) : self{
		return new self(CommonTypes::getBool($in), $isPlayerModifiable);
	}
}
