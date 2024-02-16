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

use pocketmine\network\mcpe\protocol\serializer\PacketSerializer;

final class CreativeContentEntry{
	public function __construct(
		private int $entryId,
		private ItemStack $item
	){}

	public function getEntryId() : int{ return $this->entryId; }

	public function getItem() : ItemStack{ return $this->item; }

	public static function read(PacketSerializer $in) : self{
		$entryId = $in->readCreativeItemNetId();
		$item = $in->getItemStackWithoutStackId();
		return new self($entryId, $item);
	}

	public function write(PacketSerializer $out) : void{
		$out->writeCreativeItemNetId($this->entryId);
		$out->putItemStackWithoutStackId($this->item);
	}
}
