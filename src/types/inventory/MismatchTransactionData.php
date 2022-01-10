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

use pocketmine\network\mcpe\protocol\InventoryTransactionPacket;
use pocketmine\network\mcpe\protocol\PacketDecodeException;
use pocketmine\network\mcpe\protocol\serializer\PacketSerializer;
use pocketmine\network\mcpe\protocol\types\GetTypeIdFromConstTrait;
use function count;

class MismatchTransactionData extends TransactionData{
	use GetTypeIdFromConstTrait;

	public const ID = InventoryTransactionPacket::TYPE_MISMATCH;

	protected function decodeData(PacketSerializer $stream) : void{
		if(count($this->actions) > 0){
			throw new PacketDecodeException("Mismatch transaction type should not have any actions associated with it, but got " . count($this->actions));
		}
	}

	protected function encodeData(PacketSerializer $stream) : void{

	}

	public static function new() : self{
		return new self; //no arguments
	}
}
