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
use pmmp\encoding\VarInt;
use pocketmine\network\mcpe\protocol\serializer\CommonTypes;
use function count;

final class CommandOverloadRawData{

	/**
	 * @param CommandParameterRawData[] $parameters
	 * @phpstan-param list<CommandParameterRawData> $parameters
	 */
	public function __construct(
		private bool $chaining,
		private array $parameters
	){}

	public function isChaining() : bool{ return $this->chaining; }

	/**
	 * @return CommandParameterRawData[]
	 * @phpstan-return list<CommandParameterRawData>
	 */
	public function getParameters() : array{ return $this->parameters; }

	public static function read(ByteBufferReader $in) : self{
		$chaining = CommonTypes::getBool($in);
		$parameters = [];

		for($i = 0, $size = VarInt::readUnsignedInt($in); $i < $size; $i++){
			$parameters[] = CommandParameterRawData::read($in);
		}

		return new self($chaining, $parameters);
	}

	public function write(ByteBufferWriter $out) : void{
		CommonTypes::putBool($out, $this->chaining);
		VarInt::writeUnsignedInt($out, count($this->parameters));

		foreach($this->parameters as $parameter){
			$parameter->write($out);
		}
	}
}
