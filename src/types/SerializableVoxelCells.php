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

namespace pocketmine\network\mcpe\protocol\types;

use pmmp\encoding\Byte;
use pmmp\encoding\ByteBufferReader;
use pmmp\encoding\ByteBufferWriter;
use pmmp\encoding\VarInt;
use function count;

final class SerializableVoxelCells{

	/**
	 * @param list<int> $storage
	 */
	public function __construct(
		private int $xSize,
		private int $ySize,
		private int $zSize,
		private array $storage
	){}

	public function getXSize() : int{ return $this->xSize; }

	public function getYSize() : int{ return $this->ySize; }

	public function getZSize() : int{ return $this->zSize; }

	/**
	 * @return list<int>
	 */
	public function getStorage() : array{ return $this->storage; }

	public static function read(ByteBufferReader $in) : self{
		$xSize = Byte::readUnsigned($in);
		$ySize = Byte::readUnsigned($in);
		$zSize = Byte::readUnsigned($in);

		$storage = [];
		for($i = 0, $storageCount = VarInt::readUnsignedInt($in); $i < $storageCount; ++$i){
			$storage[] = Byte::readUnsigned($in);
		}

		return new self(
			$xSize,
			$ySize,
			$zSize,
			$storage
		);
	}

	public function write(ByteBufferWriter $out) : void{
		Byte::writeUnsigned($out, $this->xSize);
		Byte::writeUnsigned($out, $this->ySize);
		Byte::writeUnsigned($out, $this->zSize);

		VarInt::writeUnsignedInt($out, count($this->storage));
		foreach($this->storage as $value){
			Byte::writeUnsigned($out, $value);
		}
	}
}
