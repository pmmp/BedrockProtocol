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
use pmmp\encoding\LE;

final class FloatGameRule extends GameRule{
	use GetTypeIdFromConstTrait;

	public const ID = GameRuleType::FLOAT;

	private float $value;

	public function __construct(float $value, bool $isPlayerModifiable){
		parent::__construct($isPlayerModifiable);
		$this->value = $value;
	}

	public function getValue() : float{
		return $this->value;
	}

	public function encode(ByteBufferWriter $out) : void{
		LE::writeFloat($out, $this->value);
	}

	public static function decode(ByteBufferReader $in, bool $isPlayerModifiable) : self{
		return new self(LE::readFloat($in), $isPlayerModifiable);
	}
}
