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

class CommandParameter{
	public const FLAG_FORCE_COLLAPSE_ENUM = 0x1;
	public const FLAG_HAS_ENUM_CONSTRAINT = 0x2;

	public string $paramName;
	public int $paramType;
	public bool $isOptional;
	public int $flags = 0; //shows enum name if 1, always zero except for in /gamerule command
	public CommandHardEnum|CommandSoftEnum|null $enum = null;
	public ?string $postfix = null;

	private static function baseline(string $name, int $type, int $flags, bool $optional) : self{
		$result = new self;
		$result->paramName = $name;
		$result->paramType = $type;
		$result->flags = $flags;
		$result->isOptional = $optional;
		return $result;
	}

	public static function standard(string $name, int $type, int $flags = 0, bool $optional = false) : self{
		return self::baseline($name, $type, $flags, $optional);
	}

	public static function postfixed(string $name, string $postfix, int $flags = 0, bool $optional = false) : self{
		$result = self::baseline($name, 0, $flags, $optional);
		$result->postfix = $postfix;
		return $result;
	}

	public static function enum(string $name, CommandHardEnum $enum, int $flags, bool $optional = false) : self{
		$result = self::baseline($name, 0, $flags, $optional);
		$result->enum = $enum;
		return $result;
	}

	public static function softEnum(string $name, CommandSoftEnum $enum, int $flags, bool $optional = false) : self{
		$result = self::baseline($name, 0, $flags, $optional);
		$result->enum = $enum;
		return $result;
	}

	/**
	 * @generate-create-func
	 */
	public static function allFields(string $paramName, int $paramType, bool $isOptional, int $flags, CommandHardEnum|CommandSoftEnum|null $enum, ?string $postfix) : self{
		$result = new self;
		$result->paramName = $paramName;
		$result->paramType = $paramType;
		$result->isOptional = $isOptional;
		$result->flags = $flags;
		$result->enum = $enum;
		$result->postfix = $postfix;
		return $result;
	}
}
