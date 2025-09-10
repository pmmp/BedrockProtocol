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

namespace pocketmine\network\mcpe\protocol\types\inventory;

use pmmp\encoding\Byte;
use pmmp\encoding\ByteBufferReader;
use pmmp\encoding\ByteBufferWriter;
use pmmp\encoding\LE;
use pocketmine\nbt\LittleEndianNbtSerializer;
use pocketmine\nbt\NbtDataException;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\nbt\TreeRoot;
use pocketmine\network\mcpe\protocol\PacketDecodeException;
use function count;
use function strlen;

/**
 * Wrapper class for extra data on ItemStacks.
 * The data is normally provided as a raw string (not automatically decoded).
 * This class is just a DTO for PacketSerializer to use when encoding/decoding ItemStacks.
 */
class ItemStackExtraData{
	/**
	 * @param string[] $canPlaceOn
	 * @param string[] $canDestroy
	 */
	public function __construct(
		private ?CompoundTag $nbt,
		private array $canPlaceOn,
		private array $canDestroy
	){}

	/**
	 * @return string[]
	 */
	public function getCanPlaceOn() : array{
		return $this->canPlaceOn;
	}

	/**
	 * @return string[]
	 */
	public function getCanDestroy() : array{
		return $this->canDestroy;
	}

	public function getNbt() : ?CompoundTag{
		return $this->nbt;
	}

	public static function read(ByteBufferReader $in) : self{
		$nbtLen = LE::readSignedShort($in);

		/** @var CompoundTag|null $compound */
		$compound = null;
		if($nbtLen === -1){
			$nbtDataVersion = Byte::readUnsigned($in);
			if($nbtDataVersion !== 1){
				throw new PacketDecodeException("Unexpected NBT data version $nbtDataVersion");
			}
			$offset = $in->getOffset();
			try{
				$compound = (new LittleEndianNbtSerializer())->read($in->getData(), $offset, 512)->mustGetCompoundTag();
			}catch(NbtDataException $e){
				throw PacketDecodeException::wrap($e, "Failed decoding NBT root");
			}finally{
				$in->setOffset($offset);
			}
		}elseif($nbtLen !== 0){
			throw new PacketDecodeException("Unexpected fake NBT length $nbtLen");
		}

		$canPlaceOn = [];
		//TODO: apparently this is not correct as of 1.21.50
		for($i = 0, $canPlaceOnCount = LE::readUnsignedInt($in); $i < $canPlaceOnCount; ++$i){
			$canPlaceOn[] = $in->readByteArray(LE::readUnsignedShort($in));
		}

		$canDestroy = [];
		for($i = 0, $canDestroyCount = LE::readUnsignedInt($in); $i < $canDestroyCount; ++$i){
			$canDestroy[] = $in->readByteArray(LE::readUnsignedShort($in));
		}

		return new self($compound, $canPlaceOn, $canDestroy);
	}

	public function write(ByteBufferWriter $out) : void{
		if($this->nbt !== null){
			LE::writeSignedShort($out, 0xffff);
			Byte::writeUnsigned($out, 1); //TODO: NBT data version (?)
			$out->writeByteArray((new LittleEndianNbtSerializer())->write(new TreeRoot($this->nbt)));
		}else{
			LE::writeSignedShort($out, 0);
		}

		LE::writeUnsignedInt($out, count($this->canPlaceOn));
		foreach($this->canPlaceOn as $entry){
			LE::writeUnsignedShort($out, strlen($entry));
			$out->writeByteArray($entry);
		}
		LE::writeUnsignedInt($out, count($this->canDestroy));
		foreach($this->canDestroy as $entry){
			LE::writeUnsignedShort($out, strlen($entry));
			$out->writeByteArray($entry);
		}
	}
}
