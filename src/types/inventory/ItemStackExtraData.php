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

use pocketmine\nbt\LittleEndianNbtSerializer;
use pocketmine\nbt\NbtDataException;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\nbt\TreeRoot;
use pocketmine\network\mcpe\protocol\PacketDecodeException;
use pocketmine\network\mcpe\protocol\serializer\PacketSerializer;
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

	public static function read(PacketSerializer $in) : self{
		$nbtLen = $in->getLShort();

		/** @var CompoundTag|null $compound */
		$compound = null;
		if($nbtLen === 0xffff){
			$nbtDataVersion = $in->getByte();
			if($nbtDataVersion !== 1){
				throw new PacketDecodeException("Unexpected NBT data version $nbtDataVersion");
			}
			$offset = $in->getOffset();
			try{
				$compound = (new LittleEndianNbtSerializer())->read($in->getBuffer(), $offset, 512)->mustGetCompoundTag();
			}catch(NbtDataException $e){
				throw PacketDecodeException::wrap($e, "Failed decoding NBT root");
			}finally{
				$in->setOffset($offset);
			}
		}elseif($nbtLen !== 0){
			throw new PacketDecodeException("Unexpected fake NBT length $nbtLen");
		}

		$canPlaceOn = [];
		for($i = 0, $canPlaceOnCount = $in->getLInt(); $i < $canPlaceOnCount; ++$i){
			$canPlaceOn[] = $in->get($in->getLShort());
		}

		$canDestroy = [];
		for($i = 0, $canDestroyCount = $in->getLInt(); $i < $canDestroyCount; ++$i){
			$canDestroy[] = $in->get($in->getLShort());
		}

		return new self($compound, $canPlaceOn, $canDestroy);
	}

	public function write(PacketSerializer $out) : void{
		if($this->nbt !== null){
			$out->putLShort(0xffff);
			$out->putByte(1); //TODO: NBT data version (?)
			$out->put((new LittleEndianNbtSerializer())->write(new TreeRoot($this->nbt)));
		}else{
			$out->putLShort(0);
		}

		$out->putLInt(count($this->canPlaceOn));
		foreach($this->canPlaceOn as $entry){
			$out->putLShort(strlen($entry));
			$out->put($entry);
		}
		$out->putLInt(count($this->canDestroy));
		foreach($this->canDestroy as $entry){
			$out->putLShort(strlen($entry));
			$out->put($entry);
		}
	}
}
