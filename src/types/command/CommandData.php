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

use function is_int;

class CommandData{
	private string $permission;

	/**
	 * @param CommandOverload[] $overloads
	 * @param ChainedSubCommandData[] $chainedSubCommandData
	 */
	public function __construct(
		public string $name,
		public string $description,
		public int $flags,
		int|string $permission,
		public ?CommandHardEnum $aliases,
		public array $overloads,
		public array $chainedSubCommandData
	){
		(function(CommandOverload ...$overloads) : void{})(...$overloads);
		(function(ChainedSubCommandData ...$chainedSubCommandData) : void{})(...$chainedSubCommandData);
		$this->permission = is_int($permission) ? CommandPermissions::toName($permission) : $permission;
	}

	public function getName() : string{
		return $this->name;
	}

	public function getDescription() : string{
		return $this->description;
	}

	public function getFlags() : int{
		return $this->flags;
	}

	public function getPermission() : string{
		return $this->permission;
	}

	public function getAliases() : ?CommandHardEnum{
		return $this->aliases;
	}

	/**
	 * @return CommandOverload[]
	 */
	public function getOverloads() : array{
		return $this->overloads;
	}

	/**
	 * @return ChainedSubCommandData[]
	 */
	public function getChainedSubCommandData() : array{
		return $this->chainedSubCommandData;
	}
}
