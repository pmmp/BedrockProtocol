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

namespace pocketmine\network\mcpe\protocol\types\entity;

trait IntegerishMetadataProperty{
	public function __construct(
		private int $value
	){
		if($value < $this->min() or $value > $this->max()){
			throw new \InvalidArgumentException("Value is out of range " . $this->min() . " - " . $this->max());
		}
	}

	abstract protected function min() : int;

	abstract protected function max() : int;

	public function getValue() : int{
		return $this->value;
	}

	public function equals(MetadataProperty $other) : bool{
		return $other instanceof self and $other->value === $this->value;
	}

	/**
	 * @param bool[] $flags
	 * @phpstan-param array<int, bool> $flags
	 */
	public static function buildFromFlags(array $flags) : self{
		$value = 0;
		foreach($flags as $flag => $v){
			if($v){
				$value |= 1 << $flag;
			}
		}
		return new self($value);
	}
}
