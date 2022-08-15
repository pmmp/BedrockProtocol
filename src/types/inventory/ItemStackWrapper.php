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

final class ItemStackWrapper{
	public function __construct(
		private int $stackId,
		private ItemStack $itemStack
	){}

	public static function legacy(ItemStack $itemStack) : self{
		return new self($itemStack->getId() === 0 ? 0 : 1, $itemStack);
	}

	public function getStackId() : int{ return $this->stackId; }

	public function getItemStack() : ItemStack{ return $this->itemStack; }

	public static function read(PacketSerializer $in) : self{
		$stackId = 0;
		$stack = $in->getItemStack(function(PacketSerializer $in) use (&$stackId) : void{
			$hasNetId = $in->getBool();
			if($hasNetId){
				$stackId = $in->readGenericTypeNetworkId();
			}
		});
		return new self($stackId, $stack);
	}

	public function write(PacketSerializer $out) : void{
		$out->putItemStack($this->itemStack, function(PacketSerializer $out) : void{
			$out->putBool($this->stackId !== 0);
			if($this->stackId !== 0){
				$out->writeGenericTypeNetworkId($this->stackId);
			}
		});
	}
}
