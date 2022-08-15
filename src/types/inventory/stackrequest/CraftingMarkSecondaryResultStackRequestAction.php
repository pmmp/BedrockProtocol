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

namespace pocketmine\network\mcpe\protocol\types\inventory\stackrequest;

use pocketmine\network\mcpe\protocol\serializer\PacketSerializer;
use pocketmine\network\mcpe\protocol\types\GetTypeIdFromConstTrait;

/**
 * I have no clear idea what this does. It seems to be the client hinting to the server "hey, put a secondary output in
 * X crafting grid slot". This is used for things like buckets.
 */
final class CraftingMarkSecondaryResultStackRequestAction extends ItemStackRequestAction{
	use GetTypeIdFromConstTrait;

	public const ID = ItemStackRequestActionType::CRAFTING_MARK_SECONDARY_RESULT_SLOT;

	public function __construct(
		private int $craftingGridSlot
	){}

	public function getCraftingGridSlot() : int{ return $this->craftingGridSlot; }

	public static function read(PacketSerializer $in) : self{
		$slot = $in->getByte();
		return new self($slot);
	}

	public function write(PacketSerializer $out) : void{
		$out->putByte($this->craftingGridSlot);
	}
}
