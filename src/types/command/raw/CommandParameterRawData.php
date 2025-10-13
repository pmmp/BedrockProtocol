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

use pmmp\encoding\Byte;
use pmmp\encoding\ByteBufferReader;
use pmmp\encoding\ByteBufferWriter;
use pmmp\encoding\LE;
use pocketmine\network\mcpe\protocol\serializer\CommonTypes;

final class CommandParameterRawData{

	public function __construct(
		private string $name,
		private int $typeInfo,
		private bool $optional,
		private int $flags
	){}

	public function getName() : string{ return $this->name; }

	public function getTypeInfo() : int{ return $this->typeInfo; }

	public function isOptional() : bool{ return $this->optional; }

	public function getFlags() : int{ return $this->flags; }

	public static function read(ByteBufferReader $in) : self{
		$name = CommonTypes::getString($in);
		$typeInfo = LE::readUnsignedInt($in);
		$optional = CommonTypes::getBool($in);
		$flags = Byte::readUnsigned($in);

		return new self($name, $typeInfo, $optional, $flags);
	}

	public function write(ByteBufferWriter $out) : void{
		CommonTypes::putString($out, $this->name);
		LE::writeUnsignedInt($out, $this->typeInfo);
		CommonTypes::putBool($out, $this->optional);
		Byte::writeUnsigned($out, $this->flags);
	}
}
