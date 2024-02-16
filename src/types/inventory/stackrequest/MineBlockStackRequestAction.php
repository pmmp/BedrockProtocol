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

final class MineBlockStackRequestAction extends ItemStackRequestAction{
	use GetTypeIdFromConstTrait;

	public const ID = ItemStackRequestActionType::MINE_BLOCK;

	public function __construct(
		private int $hotbarSlot,
		private int $predictedDurability,
		private int $stackId
	){}

	public function getHotbarSlot() : int{ return $this->hotbarSlot; }

	public function getPredictedDurability() : int{ return $this->predictedDurability; }

	public function getStackId() : int{ return $this->stackId; }

	public static function read(PacketSerializer $in) : self{
		$hotbarSlot = $in->getVarInt();
		$predictedDurability = $in->getVarInt();
		$stackId = $in->readItemStackNetIdVariant();
		return new self($hotbarSlot, $predictedDurability, $stackId);
	}

	public function write(PacketSerializer $out) : void{
		$out->putVarInt($this->hotbarSlot);
		$out->putVarInt($this->predictedDurability);
		$out->writeItemStackNetIdVariant($this->stackId);
	}
}
