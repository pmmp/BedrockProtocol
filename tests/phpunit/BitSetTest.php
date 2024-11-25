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

namespace pocketmine\network\mcpe\protocol;

use PHPUnit\Framework\TestCase;
use pocketmine\network\mcpe\protocol\serializer\BitSet;
use pocketmine\network\mcpe\protocol\serializer\PacketSerializer;

class BitSetTest extends TestCase{

	public function testBitSet() : void{
		$test = new BitSet(65);

		$test->set(0, true);
		$test->set(64, true);

		$packetSerializer = PacketSerializer::encoder();
		$test->write($packetSerializer);

		$packetSerializer = PacketSerializer::decoder($packetSerializer->getBuffer(), 0);
		$test = BitSet::read($packetSerializer, 65);

		self::assertTrue($test->get(0));
		for($i = 1; $i < 64; ++$i){
			self::assertFalse($test->get($i));
		}
		self::assertTrue($test->get(64));
	}
}
