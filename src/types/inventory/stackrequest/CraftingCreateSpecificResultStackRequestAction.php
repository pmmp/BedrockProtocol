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
use pocketmine\network\mcpe\protocol\types\inventory\ContainerUIIds;
use pocketmine\network\mcpe\protocol\types\inventory\UIInventorySlotOffset;

/**
 * This action precedes a "take" or "place" action involving the "created item" magic slot. It indicates that the
 * "created item" output slot now contains output N of a previously specified crafting recipe.
 * This is only used with crafting recipes that have multiple outputs. For recipes with single outputs, it's assumed
 * that the content of the "created item" slot is the only output.
 *
 * @see ContainerUIIds::CREATED_OUTPUT
 * @see UIInventorySlotOffset::CREATED_ITEM_OUTPUT
 */
final class CraftingCreateSpecificResultStackRequestAction extends ItemStackRequestAction{
	use GetTypeIdFromConstTrait;

	public const ID = ItemStackRequestActionType::CRAFTING_CREATE_SPECIFIC_RESULT;

	public function __construct(
		private int $resultIndex
	){}

	public function getResultIndex() : int{ return $this->resultIndex; }

	public static function read(PacketSerializer $in) : self{
		$slot = $in->getByte();
		return new self($slot);
	}

	public function write(PacketSerializer $out) : void{
		$out->putByte($this->resultIndex);
	}
}
