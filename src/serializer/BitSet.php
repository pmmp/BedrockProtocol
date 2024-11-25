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

namespace pocketmine\network\mcpe\protocol\serializer;

use pocketmine\utils\BinaryDataException;
use function array_pad;
use function array_values;
use function chr;
use function count;
use function intdiv;
use function ord;

class BitSet{
	private const INT_BITS = PHP_INT_SIZE * 8;
	private const SHIFT = 7;

	/**
	 * @param int[] $parts
	 */
	public function __construct(
		private readonly int $length,
		private array $parts = []
	){
		$expectedPartsCount = intdiv($length + self::INT_BITS - 1, self::INT_BITS);
		$partsCount = count($parts);

		if($partsCount > $expectedPartsCount){
			throw new \InvalidArgumentException("Too many parts");
		}elseif($partsCount < $expectedPartsCount){
			$parts = array_pad($parts, $expectedPartsCount, 0);
		}

		$this->parts = array_values($parts);
	}

	public function get(int $index) : bool{
		[$partIndex, $bitIndex] = $this->getPartIndex($index);

		return ($this->parts[$partIndex] & (1 << $bitIndex)) !== 0;
	}

	public function set(int $index, bool $value) : void{
		[$partIndex, $bitIndex] = $this->getPartIndex($index);

		if($value){
			$this->parts[$partIndex] |= 1 << $bitIndex;
		}else{
			$this->parts[$partIndex] &= ~(1 << $bitIndex);
		}
	}

	/**
	 * Returns the part index and the bit index within that part for a given bit index.
	 *
	 * @return array{int, int}
	 */
	private function getPartIndex(int $index) : array{
		if($index < 0 or $index >= $this->length){
			throw new \InvalidArgumentException("Index out of bounds");
		}

		return [
			intdiv($index, self::INT_BITS),
			$index % self::INT_BITS
		];
	}

	public static function read(PacketSerializer $in, int $length) : self{
		$result = [0];

		$currentIndex = 0;
		$currentShift = 0;

		for($i = 0; $i < $length; $i += self::SHIFT){
			$b = ord($in->get(1));
			$bits = $b & 0x7f;

			$result[$currentIndex] |= $bits << $currentShift; //extra bits will be discarded
			$nextShift = $currentShift + self::SHIFT;
			if($nextShift >= self::INT_BITS){
				$nextShift -= self::INT_BITS;
				$rightShift = self::SHIFT - $nextShift;
				$result[++$currentIndex] = $bits >> $rightShift;
			}
			$currentShift = $nextShift;

			if(($b & 0x80) === 0){
				return new self($length, $result);
			}
		}

		throw new BinaryDataException("Didn't terminate after reading $length bits");
	}

	public function write(PacketSerializer $out) : void{
		$buf = "";

		$parts = $this->parts;
		$length = $this->length;

		$currentIndex = 0;
		$currentShift = 0;

		for($i = 0; $i < $length; $i += self::SHIFT){
			$bits = $parts[$currentIndex] >> $currentShift;
			$nextShift = $currentShift + self::SHIFT;
			if($nextShift >= self::INT_BITS){
				$nextShift -= self::INT_BITS;
				$bits |= $parts[++$currentIndex] << (self::SHIFT - $nextShift);
			}
			$currentShift = $nextShift;

			$last = $i + self::SHIFT >= $length;
			$bits |= $last ? 0 : 0x80;

			$buf .= chr($bits);
			if($last){
				break;
			}
		}

		$out->put($buf);
	}

	public function getLength() : int{
		return $this->length;
	}
}
