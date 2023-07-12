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

final class CommandOverload{

	/**
	 * @param CommandParameter[] $parameters
	 */
	public function __construct(
		private bool $chaining,
		private array $parameters
	){
		(function(CommandParameter ...$parameters) : void{})(...$parameters);
	}

	public function isChaining() : bool{ return $this->chaining; }

	/**
	 * @return CommandParameter[]
	 */
	public function getParameters() : array{ return $this->parameters; }
}
