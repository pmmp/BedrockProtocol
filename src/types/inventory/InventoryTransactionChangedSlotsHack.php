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
use pmmp\encoding\VarInt;
use function count;

final class InventoryTransactionChangedSlotsHack{
	/**
	 * @param int[] $changedSlotIndexes
	 */
	public function __construct(
		private int $containerId,
		private array $changedSlotIndexes
	){}

	public function getContainerId() : int{ return $this->containerId; }

	/** @return int[] */
	public function getChangedSlotIndexes() : array{ return $this->changedSlotIndexes; }

	public static function read(ByteBufferReader $in) : self{
		$containerId = Byte::readUnsigned($in);
		$changedSlots = [];
		for($i = 0, $len = VarInt::readUnsignedInt($in); $i < $len; ++$i){
			$changedSlots[] = Byte::readUnsigned($in);
		}
		return new self($containerId, $changedSlots);
	}

	public function write(ByteBufferWriter $out) : void{
		Byte::writeUnsigned($out, $this->containerId);
		VarInt::writeUnsignedInt($out, count($this->changedSlotIndexes));
		foreach($this->changedSlotIndexes as $index){
			Byte::writeUnsigned($out, $index);
		}
	}
}
