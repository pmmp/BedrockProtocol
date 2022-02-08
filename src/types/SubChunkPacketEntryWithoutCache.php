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

use pocketmine\network\mcpe\protocol\serializer\PacketSerializer;

final class SubChunkPacketEntryWithoutCache{

	public function __construct(
		private SubChunkPacketEntryCommon $base
	){}

	public function getBase() : SubChunkPacketEntryCommon{ return $this->base; }

	public static function read(PacketSerializer $in) : self{
		return new self(SubChunkPacketEntryCommon::read($in, false));
	}

	public function write(PacketSerializer $out) : void{
		$this->base->write($out, false);
	}
}
