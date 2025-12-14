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

namespace pocketmine\network\mcpe\protocol\types\command\raw;

use pmmp\encoding\ByteBufferReader;
use pmmp\encoding\ByteBufferWriter;
use pmmp\encoding\LE;
use pmmp\encoding\VarInt;
use pocketmine\network\mcpe\protocol\serializer\CommonTypes;
use function count;

final class CommandRawData{

	/**
	 * @param int[] $chainedSubCommandDataIndexes
	 * @param CommandOverloadRawData[] $overloads
	 * @phpstan-param list<int> $chainedSubCommandDataIndexes
	 * @phpstan-param list<CommandOverloadRawData> $overloads
	 */
	public function __construct(
		private string $name,
		private string $description,
		private int $flags,
		private string $permission,
		private int $aliasEnumIndex,
		private array $chainedSubCommandDataIndexes,
		private array $overloads,
	){}

	public function getName() : string{ return $this->name; }

	public function getDescription() : string{ return $this->description; }

	public function getFlags() : int{ return $this->flags; }

	public function getPermission() : string{ return $this->permission; }

	public function getAliasEnumIndex() : int{ return $this->aliasEnumIndex; }

	/**
	 * @return int[]
	 * @phpstan-return list<int>
	 */
	public function getChainedSubCommandDataIndexes() : array{ return $this->chainedSubCommandDataIndexes; }

	/**
	 * @return CommandOverloadRawData[]
	 * @phpstan-return list<CommandOverloadRawData>
	 */
	public function getOverloads() : array{ return $this->overloads; }

	public static function read(ByteBufferReader $in) : self{
		$name = CommonTypes::getString($in);
		$description = CommonTypes::getString($in);
		$flags = LE::readUnsignedShort($in);
		$permission = CommonTypes::getString($in);
		$aliasEnumIndex = LE::readSignedInt($in); //may be -1 for not set

		$chainedSubCommandDataIndexes = [];
		for($i = 0, $size = VarInt::readUnsignedInt($in); $i < $size; $i++){
			$chainedSubCommandDataIndexes[] = LE::readUnsignedInt($in);
		}

		$overloads = [];
		for($i = 0, $size = VarInt::readUnsignedInt($in); $i < $size; $i++){
			$overloads[] = CommandOverloadRawData::read($in);
		}

		return new self(
			name: $name,
			description: $description,
			flags: $flags,
			permission: $permission,
			aliasEnumIndex: $aliasEnumIndex,
			chainedSubCommandDataIndexes: $chainedSubCommandDataIndexes,
			overloads: $overloads
		);
	}

	public function write(ByteBufferWriter $out) : void{
		CommonTypes::putString($out, $this->name);
		CommonTypes::putString($out, $this->description);
		LE::writeUnsignedShort($out, $this->flags);
		CommonTypes::putString($out, $this->permission);
		LE::writeSignedInt($out, $this->aliasEnumIndex);

		VarInt::writeUnsignedInt($out, count($this->chainedSubCommandDataIndexes));
		foreach($this->chainedSubCommandDataIndexes as $index){
			LE::writeUnsignedInt($out, $index);
		}

		VarInt::writeUnsignedInt($out, count($this->overloads));
		foreach($this->overloads as $overload){
			$overload->write($out);
		}
	}
}
