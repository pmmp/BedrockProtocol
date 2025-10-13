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

final class ConstrainedEnumValue{
	public const REQUIRES_CHEATS_ENABLED = 1 << 0;
	public const REQUIRES_ELEVATED_PERMISSIONS = 1 << 1;
	public const REQUIRES_HOST_PERMISSIONS = 1 << 2;
	public const REQUIRES_ALLOW_ALIASES = 1 << 3;

	/**
	 * @param int[] $constraints
	 * @phpstan-param list<int> $constraints
	 */
	public function __construct(
		private string $value,
		private array $constraints
	){
		self::intArrayCheck(...$this->constraints);
	}

	private static function intArrayCheck(int ...$v) : void{
		//NOOP
	}

	public function getValue() : string{ return $this->value; }

	/**
	 * @return int[]
	 * @phpstan-return list<int>
	 */
	public function getConstraints() : array{ return $this->constraints; }
}
