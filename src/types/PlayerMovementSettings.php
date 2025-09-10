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
use pmmp\encoding\VarInt;
use pocketmine\network\mcpe\protocol\serializer\CommonTypes;

final class PlayerMovementSettings{
	public function __construct(
		private int $rewindHistorySize,
		private bool $serverAuthoritativeBlockBreaking
	){}

	public function getRewindHistorySize() : int{ return $this->rewindHistorySize; }

	public function isServerAuthoritativeBlockBreaking() : bool{ return $this->serverAuthoritativeBlockBreaking; }

	public static function read(ByteBufferReader $in) : self{
		$rewindHistorySize = VarInt::readSignedInt($in);
		$serverAuthBlockBreaking = CommonTypes::getBool($in);
		return new self($rewindHistorySize, $serverAuthBlockBreaking);
	}

	public function write(ByteBufferWriter $out) : void{
		VarInt::writeSignedInt($out, $this->rewindHistorySize);
		CommonTypes::putBool($out, $this->serverAuthoritativeBlockBreaking);
	}
}
