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
use pocketmine\network\mcpe\protocol\InventoryTransactionPacket;
use pocketmine\network\mcpe\protocol\types\GetTypeIdFromConstTrait;

class NormalTransactionData extends TransactionData{
	use GetTypeIdFromConstTrait;

	public const ID = InventoryTransactionPacket::TYPE_NORMAL;

	protected function decodeData(ByteBufferReader $in) : void{

	}

	protected function encodeData(ByteBufferWriter $out) : void{

	}

	/**
	 * @param NetworkInventoryAction[] $actions
	 */
	public static function new(array $actions) : self{
		$result = new self();
		$result->actions = $actions;
		return $result;
	}
}
