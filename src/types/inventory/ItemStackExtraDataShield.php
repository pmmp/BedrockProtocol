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

use pmmp\encoding\ByteBufferReader;
use pmmp\encoding\ByteBufferWriter;
use pmmp\encoding\LE;
use pocketmine\nbt\tag\CompoundTag;

/**
 * Extension of ItemStackExtraData for shield items, which have an additional field for the blocking tick.
 */
final class ItemStackExtraDataShield extends ItemStackExtraData{

	/**
	 * @param string[] $canPlaceOn
	 * @param string[] $canDestroy
	 */
	public function __construct(
		?CompoundTag $nbt,
		array $canPlaceOn,
		array $canDestroy,
		private int $blockingTick
	){
		parent::__construct($nbt, $canPlaceOn, $canDestroy);
	}

	public function getBlockingTick() : int{ return $this->blockingTick; }

	public static function read(ByteBufferReader $in) : self{
		$base = parent::read($in);
		//TODO: I don't know for sure if this is supposed to be signed or unsigned
		$blockingTick = LE::readSignedLong($in);

		return new self($base->getNbt(), $base->getCanPlaceOn(), $base->getCanDestroy(), $blockingTick);
	}

	public function write(ByteBufferWriter $out) : void{
		parent::write($out);
		LE::writeSignedLong($out, $this->blockingTick);
	}
}
