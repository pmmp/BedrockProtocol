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
		$writeTest = new BitSet(65);

		$writeTest->set(0, true);
		$writeTest->set(64, true);

		$packetSerializer = PacketSerializer::encoder();
		$writeTest->write($packetSerializer);

		$packetSerializer = PacketSerializer::decoder($packetSerializer->getBuffer(), 0);
		$readTest = BitSet::read($packetSerializer, 65);

		self::assertEqualBitSets($writeTest, $readTest);
	}

	public function testBitSetConstructor() : void{
		$test = new BitSet(65, [-9223372036854775807 - 1, 1]);
		$test2 = new BitSet(65, [-9223372036854775807 - 1]);

		$test2->set(64, true);

		$packetSerializer = PacketSerializer::encoder();
		$test->write($packetSerializer);

		$packetSerializer2 = PacketSerializer::encoder();
		$test2->write($packetSerializer2);

		self::assertEquals($packetSerializer->getBuffer(), $packetSerializer2->getBuffer());
	}

	public function testBitSetParts() : void{
		$writeTest = new BitSet(128);
		$writeTest->set(127, true);

		$packetSerializer = PacketSerializer::encoder();
		$writeTest->write($packetSerializer);

		$packetSerializer = PacketSerializer::decoder($packetSerializer->getBuffer(), 0);
		$readTest = BitSet::read($packetSerializer, 128);

		self::assertEqualBitSets($writeTest, $readTest);
	}

	public function testVarUnsignedLongCompatibility() : void{
		$packetSerializer = PacketSerializer::encoder();
		$packetSerializer->putUnsignedVarLong(0 | 1 << 63);

		$packetSerializer = PacketSerializer::decoder($packetSerializer->getBuffer(), 0);
		$readTest = BitSet::read($packetSerializer, 64);

		$expectedResult = new BitSet(64);
		$expectedResult->set(63, true);

		self::assertEqualBitSets($expectedResult, $readTest);
	}

	private static function assertEqualBitSets(BitSet $a, BitSet $b) : void{
		self::assertEquals($length = $a->getLength(), $b->getLength(), "BitSet lengths are not equal");

		for($i = 0; $i < $length; ++$i){
			self::assertEquals($a->get($i), $b->get($i), "BitSet values at index $i are not equal");
		}

		self::assertEquals($a->getPartsCount(), $b->getPartsCount(), "BitSet parts count is not equal");

		self::assertTrue($a->equals($b));
		$b->set($b->getLength() - 1, !$b->get($b->getLength() - 1));
		self::assertFalse($a->equals($b), "BitSet equality check failed after modifying one BitSet");
	}
}
