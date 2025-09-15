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
use pocketmine\math\Vector3;
use pocketmine\network\mcpe\protocol\InventoryTransactionPacket;
use pocketmine\network\mcpe\protocol\serializer\CommonTypes;
use pocketmine\network\mcpe\protocol\types\GetTypeIdFromConstTrait;

class UseItemOnEntityTransactionData extends TransactionData{
	use GetTypeIdFromConstTrait;

	public const ID = InventoryTransactionPacket::TYPE_USE_ITEM_ON_ENTITY;

	public const ACTION_INTERACT = 0;
	public const ACTION_ATTACK = 1;
	public const ACTION_ITEM_INTERACT = 2;

	private int $actorRuntimeId;
	private int $actionType;
	private int $hotbarSlot;
	private ItemStackWrapper $itemInHand;
	private Vector3 $playerPosition;
	private Vector3 $clickPosition;

	public function getActorRuntimeId() : int{
		return $this->actorRuntimeId;
	}

	public function getActionType() : int{
		return $this->actionType;
	}

	public function getHotbarSlot() : int{
		return $this->hotbarSlot;
	}

	public function getItemInHand() : ItemStackWrapper{
		return $this->itemInHand;
	}

	public function getPlayerPosition() : Vector3{
		return $this->playerPosition;
	}

	public function getClickPosition() : Vector3{
		return $this->clickPosition;
	}

	protected function decodeData(ByteBufferReader $in) : void{
		$this->actorRuntimeId = CommonTypes::getActorRuntimeId($in);
		$this->actionType = VarInt::readUnsignedInt($in);
		$this->hotbarSlot = VarInt::readSignedInt($in);
		$this->itemInHand = CommonTypes::getItemStackWrapper($in);
		$this->playerPosition = CommonTypes::getVector3($in);
		$this->clickPosition = CommonTypes::getVector3($in);
	}

	protected function encodeData(ByteBufferWriter $out) : void{
		CommonTypes::putActorRuntimeId($out, $this->actorRuntimeId);
		VarInt::writeUnsignedInt($out, $this->actionType);
		VarInt::writeSignedInt($out, $this->hotbarSlot);
		CommonTypes::putItemStackWrapper($out, $this->itemInHand);
		CommonTypes::putVector3($out, $this->playerPosition);
		CommonTypes::putVector3($out, $this->clickPosition);
	}

	/**
	 * @param NetworkInventoryAction[] $actions
	 */
	public static function new(array $actions, int $actorRuntimeId, int $actionType, int $hotbarSlot, ItemStackWrapper $itemInHand, Vector3 $playerPosition, Vector3 $clickPosition) : self{
		$result = new self;
		$result->actions = $actions;
		$result->actorRuntimeId = $actorRuntimeId;
		$result->actionType = $actionType;
		$result->hotbarSlot = $hotbarSlot;
		$result->itemInHand = $itemInHand;
		$result->playerPosition = $playerPosition;
		$result->clickPosition = $clickPosition;
		return $result;
	}
}
