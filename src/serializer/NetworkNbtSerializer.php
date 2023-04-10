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

use pocketmine\nbt\BaseNbtSerializer;
use pocketmine\nbt\NbtDataException;
use function count;
use function strlen;

class NetworkNbtSerializer extends BaseNbtSerializer{

	public function readShort() : int{
		return $this->buffer->getLShort();
	}

	public function readSignedShort() : int{
		return $this->buffer->getSignedLShort();
	}

	public function writeShort(int $v) : void{
		$this->buffer->putLShort($v);
	}

	public function readInt() : int{
		return $this->buffer->getVarInt();
	}

	public function writeInt(int $v) : void{
		$this->buffer->putVarInt($v);
	}

	public function readLong() : int{
		return $this->buffer->getVarLong();
	}

	public function writeLong(int $v) : void{
		$this->buffer->putVarLong($v);
	}

	public function readString() : string{
		return $this->buffer->get(self::checkReadStringLength($this->buffer->getUnsignedVarInt()));
	}

	public function writeString(string $v) : void{
		$this->buffer->putUnsignedVarInt(self::checkWriteStringLength(strlen($v)));
		$this->buffer->put($v);
	}

	public function readFloat() : float{
		return $this->buffer->getLFloat();
	}

	public function writeFloat(float $v) : void{
		$this->buffer->putLFloat($v);
	}

	public function readDouble() : float{
		return $this->buffer->getLDouble();
	}

	public function writeDouble(float $v) : void{
		$this->buffer->putLDouble($v);
	}

	public function readIntArray() : array{
		$len = $this->readInt(); //varint
		if($len < 0){
			throw new NbtDataException("Array length cannot be less than zero ($len < 0)");
		}
		$ret = [];
		for($i = 0; $i < $len; ++$i){
			$ret[] = $this->readInt(); //varint
		}

		return $ret;
	}

	public function writeIntArray(array $array) : void{
		$this->writeInt(count($array)); //varint
		foreach($array as $v){
			$this->writeInt($v); //varint
		}
	}
}
