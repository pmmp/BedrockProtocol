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

final class ChainedSubCommandRawData{

	/**
	 * @param ChainedSubCommandValueRawData[] $valueData
	 * @phpstan-param list<ChainedSubCommandValueRawData> $valueData
	 */
	public function __construct(
		private string $name,
		private array $valueData
	){}

	public function getName() : string{ return $this->name; }

	/**
	 * @return ChainedSubCommandValueRawData[]
	 * @phpstan-return list<ChainedSubCommandValueRawData>
	 */
	public function getValueData() : array{ return $this->valueData; }

	public static function read(ByteBufferReader $in) : self{
		$name = CommonTypes::getString($in);

		$valueData = [];
		for($i = 0, $size = VarInt::readUnsignedInt($in); $i < $size; $i++){
			$valueData[] = ChainedSubCommandValueRawData::read($in);
		}

		return new self($name, $valueData);
	}

	public function write(ByteBufferWriter $out) : void{
		CommonTypes::putString($out, $this->name);

		VarInt::writeUnsignedInt($out, count($this->valueData));
		foreach($this->valueData as $valueDatum){
			$valueDatum->write($out);
		}
	}

}
