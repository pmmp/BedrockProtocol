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
use pmmp\encoding\VarInt;
use pocketmine\network\mcpe\protocol\serializer\CommonTypes;
use function count;

final class CommandEnumRawData{

	/**
	 * @param int[] $valueIndexes
	 * @phpstan-param list<int> $valueIndexes
	 */
	public function __construct(
		private string $name,
		private array $valueIndexes
	){}

	public function getName() : string{ return $this->name; }

	/**
	 * @return int[]
	 * @phpstan-return list<int>
	 */
	public function getValueIndexes() : array{ return $this->valueIndexes; }

	public static function read(ByteBufferReader $in, int $valueListSize) : self{
		$name = CommonTypes::getString($in);
		$valueIndexes = [];
		$size = VarInt::readUnsignedInt($in);

		for($i = 0; $i < $size; $i++){
			$valueIndexes[] = match(true){
				$valueListSize < 256 => Byte::readUnsigned($in),
				$valueListSize < 65536 => LE::readUnsignedShort($in),
				default => LE::readUnsignedInt($in)
			};
		}

		return new self($name, $valueIndexes);
	}

	public function write(ByteBufferWriter $out, int $valueListSize) : void{
		CommonTypes::putString($out, $this->name);
		VarInt::writeUnsignedInt($out, count($this->valueIndexes));

		foreach($this->valueIndexes as $index){
			match(true){
				$valueListSize < 256 => Byte::writeUnsigned($out, $index),
				$valueListSize < 65536 => LE::writeUnsignedShort($out, $index),
				default => LE::writeUnsignedInt($out, $index)
			};
		}
	}
}
