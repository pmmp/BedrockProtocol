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

final class SubChunkPacketEntryWithCache{

	public function __construct(
		private SubChunkPacketEntryCommon $base,
		private int $usedBlobHash
	){}

	public function getBase() : SubChunkPacketEntryCommon{ return $this->base; }

	public function getUsedBlobHash() : int{ return $this->usedBlobHash; }

	public static function read(ByteBufferReader $in) : self{
		$base = SubChunkPacketEntryCommon::read($in, true);
		$usedBlobHash = LE::readUnsignedLong($in);

		return new self($base, $usedBlobHash);
	}

	public function write(ByteBufferWriter $out) : void{
		$this->base->write($out, true);
		LE::writeUnsignedLong($out, $this->usedBlobHash);
	}
}
