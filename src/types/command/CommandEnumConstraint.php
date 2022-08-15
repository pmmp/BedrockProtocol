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

class CommandEnumConstraint{
	public const REQUIRES_CHEATS_ENABLED = 1 << 0;
	public const REQUIRES_ELEVATED_PERMISSIONS = 1 << 1;
	public const REQUIRES_HOST_PERMISSIONS = 1 << 2;
	public const REQUIRES_ALLOW_ALIASES = 1 << 3;

	/**
	 * @param int[]       $constraints
	 */
	public function __construct(
		private CommandEnum $enum,
		private int $valueOffset,
		private array $constraints
	){
		(static function(int ...$_) : void{})(...$constraints);
		if(!isset($enum->getValues()[$valueOffset])){
			throw new \InvalidArgumentException("Invalid enum value offset $valueOffset");
		}
	}

	public function getEnum() : CommandEnum{
		return $this->enum;
	}

	public function getValueOffset() : int{
		return $this->valueOffset;
	}

	public function getAffectedValue() : string{
		return $this->enum->getValues()[$this->valueOffset];
	}

	/**
	 * @return int[]
	 */
	public function getConstraints() : array{
		return $this->constraints;
	}
}
