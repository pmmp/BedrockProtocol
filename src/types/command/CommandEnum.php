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

namespace pocketmine\network\mcpe\protocol\types\command;

use function count;
use function is_array;
use function is_int;

class CommandEnum{
	public const CONSTRAINT_REQUIRES_CHEATS_ENABLED = 1 << 0;
	public const CONSTRAINT_REQUIRES_ELEVATED_PERMISSIONS = 1 << 1;
	public const CONSTRAINT_REQUIRES_HOST_PERMISSIONS = 1 << 2;
	public const CONSTRAINT_REQUIRES_ALLOW_ALIASES = 1 << 3;

	/**
	 * @param string[] $enumValues
	 * @param bool     $isSoft Whether the enum is dynamic, i.e. can be updated during the game session
	 * @param int[][]  $constraints
	 *
	 * @phpstan-param list<string> $enumValues
	 * @phpstan-param array<int, list<int>> $constraints
	 */
	public function __construct(
		private string $enumName,
		private array $enumValues,
		private bool $isSoft = false,
		private array $constraints = [],
	){
		if($this->isSoft && count($this->constraints) > 0){
			throw new \InvalidArgumentException("Cannot add constraints for the values of a soft enum");
		}
		foreach($this->constraints as $valueOffset => $eachConstraints){
			if(!isset($this->enumValues[$valueOffset])){
				throw new \InvalidArgumentException("No such enum value offset $valueOffset");
			}
			foreach($eachConstraints as $constraintSetOffset => $constraintSet){
				if(!is_array($constraintSet)){
					throw new \InvalidArgumentException("Expected an array of ints for constraints[$valueOffset][$constraintSetOffset]");
				}
				foreach($constraintSet as $constraintOffset => $constraint){
					if(!is_int($constraint)){
						throw new \InvalidArgumentException("Expected int for constraints[$valueOffset][$constraintSetOffset][$constraintOffset]");
					}
				}
			}
		}
	}

	public function getName() : string{
		return $this->enumName;
	}

	/**
	 * @return string[]
	 * @phpstan-return list<string>
	 */
	public function getValues() : array{
		return $this->enumValues;
	}

	/**
	 * @return int[][]
	 * @phpstan-return array<int, list<int>>
	 */
	public function getConstraints() : array{ return $this->constraints; }

	/**
	 * @return bool Whether the enum is dynamic, i.e. can be updated during the game session
	 */
	public function isSoft() : bool{
		return $this->isSoft;
	}
}
