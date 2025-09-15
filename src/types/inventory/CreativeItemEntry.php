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
use pmmp\encoding\VarInt;
use pocketmine\network\mcpe\protocol\serializer\CommonTypes;

final class CreativeItemEntry{
	public function __construct(
		private int $entryId,
		private ItemStack $item,
		private int $groupId
	){}

	public function getEntryId() : int{ return $this->entryId; }

	public function getItem() : ItemStack{ return $this->item; }

	public function getGroupId() : int{ return $this->groupId; }

	public static function read(ByteBufferReader $in) : self{
		$entryId = CommonTypes::readCreativeItemNetId($in);
		$item = CommonTypes::getItemStackWithoutStackId($in);
		$groupId = VarInt::readUnsignedInt($in);
		return new self($entryId, $item, $groupId);
	}

	public function write(ByteBufferWriter $out) : void{
		CommonTypes::writeCreativeItemNetId($out, $this->entryId);
		CommonTypes::putItemStackWithoutStackId($out, $this->item);
		VarInt::writeUnsignedInt($out, $this->groupId);
	}
}
