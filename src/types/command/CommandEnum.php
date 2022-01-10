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

class CommandEnum{

	private string $enumName;
	/**
	 * @var string[]
	 * @phpstan-var list<string>
	 */
	private array $enumValues = [];

	/**
	 * @param string[] $enumValues
	 * @phpstan-param list<string> $enumValues
	 */
	public function __construct(string $enumName, array $enumValues){
		$this->enumName = $enumName;
		$this->enumValues = $enumValues;
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
}
