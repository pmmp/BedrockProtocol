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

	private CommandEnum $enum;
	private int $valueOffset;
	/** @var int[] */
	private array $constraints; //TODO: find constants

	/**
	 * @param int[]       $constraints
	 */
	public function __construct(CommandEnum $enum, int $valueOffset, array $constraints){
		(static function(int ...$_) : void{})(...$constraints);
		if(!isset($enum->getValues()[$valueOffset])){
			throw new \InvalidArgumentException("Invalid enum value offset $valueOffset");
		}
		$this->enum = $enum;
		$this->valueOffset = $valueOffset;
		$this->constraints = $constraints;
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
