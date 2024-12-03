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
use function PHPUnit\Framework\assertTrue;
use function var_dump;

class BitSetTest extends TestCase{

	public function testBitSet() : void{
		$writeTest = new BitSet(65);

		$writeTest->set(0, true);
		$writeTest->set(64, true);

		$packetSerializer = PacketSerializer::encoder();
		$writeTest->write($packetSerializer);

		$packetSerializer = PacketSerializer::decoder($packetSerializer->getBuffer(), 0);
		$readTest = BitSet::read($packetSerializer, 65);

		assertTrue($this->setsEqual($writeTest, $readTest));
	}

	public function testBitSetConstructor() : void{
		$test = new BitSet(65, [-9223372036854775808, 1]);
		$test2 = new BitSet(65, [-9223372036854775808]);

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

		assertTrue($this->setsEqual($writeTest, $readTest));
	}

	public function testVarUnsignedLongCompatibility() : void{
		$packetSerializer = PacketSerializer::encoder();
		$packetSerializer->putUnsignedVarLong(0 | 1 << 63);

		$packetSerializer = PacketSerializer::decoder($packetSerializer->getBuffer(), 0);
		$readTest = BitSet::read($packetSerializer, 64);

		$expectedResult = new BitSet(64);
		$expectedResult->set(63, true);

		assertTrue($this->setsEqual($expectedResult, $readTest));
	}

	private function setsEqual(BitSet $a, BitSet $b) : bool{
		$length = $a->getLength();
		if($length !== $b->getLength()){
			var_dump($length, $b->getLength());
			return false;
		}

		for($i = 0; $i < $length; ++$i){
			if($a->get($i) !== $b->get($i)){
				var_dump($i, $a->get($i), $b->get($i));
				return false;
			}
		}

		return $a->getPartsCount() === $b->getPartsCount();
	}
}
