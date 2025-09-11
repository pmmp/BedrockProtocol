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

namespace pocketmine\network\mcpe\protocol\serializer;

use pmmp\encoding\LE;
use pmmp\encoding\VarInt;
use pocketmine\nbt\BaseNbtSerializer;
use pocketmine\nbt\NbtDataException;
use function count;
use function strlen;

class NetworkNbtSerializer extends BaseNbtSerializer{

	public function readShort() : int{
		return LE::readUnsignedShort($this->reader);
	}

	public function readSignedShort() : int{
		return LE::readSignedShort($this->reader);
	}

	public function writeShort(int $v) : void{
		//TODO: signed and unsigned are encoded the same, but this API should be redesigned
		LE::writeSignedShort($this->writer, $v);
	}

	public function readInt() : int{
		return VarInt::readSignedInt($this->reader);
	}

	public function writeInt(int $v) : void{
		VarInt::writeSignedInt($this->writer, $v);
	}

	public function readLong() : int{
		return VarInt::readSignedLong($this->reader);
	}

	public function writeLong(int $v) : void{
		VarInt::writeSignedLong($this->writer, $v);
	}

	public function readString() : string{
		return $this->reader->readByteArray(self::checkReadStringLength(VarInt::readUnsignedInt($this->reader)));
	}

	public function writeString(string $v) : void{
		VarInt::writeUnsignedInt($this->writer, self::checkWriteStringLength(strlen($v)));
		$this->writer->writeByteArray($v);
	}

	public function readFloat() : float{
		return LE::readFloat($this->reader);
	}

	public function writeFloat(float $v) : void{
		LE::writeFloat($this->writer, $v);
	}

	public function readDouble() : float{
		return LE::readDouble($this->reader);
	}

	public function writeDouble(float $v) : void{
		LE::writeDouble($this->writer, $v);
	}

	public function readIntArray() : array{
		$len = VarInt::readSignedInt($this->reader);
		if($len < 0){
			throw new NbtDataException("Array length cannot be less than zero ($len < 0)");
		}
		$result = [];
		for($i = 0; $i < $len; ++$i){
			$result[] = VarInt::readSignedInt($this->reader);
		}
		return $result;
	}

	public function writeIntArray(array $array) : void{
		VarInt::writeSignedInt($this->writer, count($array));
		foreach($array as $v){
			VarInt::writeSignedInt($this->writer, $v);
		}
	}
}
