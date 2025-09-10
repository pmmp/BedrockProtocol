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

use pmmp\encoding\ByteBufferWriter;

abstract class GameRule{
	public function __construct(
		private bool $isPlayerModifiable
	){}

	public function isPlayerModifiable() : bool{ return $this->isPlayerModifiable; }

	abstract public function getTypeId() : int;

	abstract public function encode(ByteBufferWriter $out) : void;
}
