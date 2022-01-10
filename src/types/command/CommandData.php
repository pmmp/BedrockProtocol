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

class CommandData{
	public string $name;
	public string $description;
	public int $flags;
	public int $permission;
	public ?CommandEnum $aliases;
	/** @var CommandParameter[][] */
	public array $overloads = [];

	/**
	 * @param CommandParameter[][] $overloads
	 */
	public function __construct(string $name, string $description, int $flags, int $permission, ?CommandEnum $aliases, array $overloads){
		(function(array ...$overloads) : void{
			foreach($overloads as $overload){
				(function(CommandParameter ...$parameters) : void{})(...$overload);
			}
		})(...$overloads);
		$this->name = $name;
		$this->description = $description;
		$this->flags = $flags;
		$this->permission = $permission;
		$this->aliases = $aliases;
		$this->overloads = $overloads;
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

	public function getPermission() : int{
		return $this->permission;
	}

	public function getAliases() : ?CommandEnum{
		return $this->aliases;
	}

	/**
	 * @return CommandParameter[][]
	 */
	public function getOverloads() : array{
		return $this->overloads;
	}
}
