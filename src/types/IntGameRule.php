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
use pmmp\encoding\VarInt;

final class IntGameRule extends GameRule{
	use GetTypeIdFromConstTrait;

	public const ID = GameRuleType::INT;

	private int $value;

	public function __construct(int $value, bool $isPlayerModifiable){
		parent::__construct($isPlayerModifiable);
		$this->value = $value;
	}

	public function getValue() : int{
		return $this->value;
	}

	public function encode(ByteBufferWriter $out, bool $isStartGame) : void{
		if($isStartGame){
			VarInt::writeUnsignedInt($out, $this->value);
		}else{
			LE::writeUnsignedInt($out, $this->value);
		}
	}

	public static function decode(ByteBufferReader $in, bool $isPlayerModifiable, bool $isStartGame) : self{
		return new self($isStartGame ? VarInt::readUnsignedInt($in) : LE::readUnsignedInt($in), $isPlayerModifiable);
	}
}
