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
use pocketmine\network\mcpe\protocol\types\BlockPosition;
use pocketmine\network\mcpe\protocol\types\GetTypeIdFromConstTrait;

class UseItemTransactionData extends TransactionData{
	use GetTypeIdFromConstTrait;

	public const ID = InventoryTransactionPacket::TYPE_USE_ITEM;

	public const ACTION_CLICK_BLOCK = 0;
	public const ACTION_CLICK_AIR = 1;
	public const ACTION_BREAK_BLOCK = 2;
	public const ACTION_USE_AS_ATTACK = 3;

	private int $actionType;
	private TriggerType $triggerType;
	private BlockPosition $blockPosition;
	private int $face;
	private int $hotbarSlot;
	private ItemStackWrapper $itemInHand;
	private Vector3 $playerPosition;
	private Vector3 $clickPosition;
	private int $blockRuntimeId;
	private PredictedResult $clientInteractPrediction;

	public function getActionType() : int{
		return $this->actionType;
	}

	public function getTriggerType() : TriggerType{ return $this->triggerType; }

	public function getBlockPosition() : BlockPosition{
		return $this->blockPosition;
	}

	public function getFace() : int{
		return $this->face;
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

	public function getBlockRuntimeId() : int{
		return $this->blockRuntimeId;
	}

	public function getClientInteractPrediction() : PredictedResult{ return $this->clientInteractPrediction; }

	protected function decodeData(ByteBufferReader $in) : void{
		$this->actionType = VarInt::readUnsignedInt($in);
		$this->triggerType = TriggerType::fromPacket(VarInt::readUnsignedInt($in));
		$this->blockPosition = CommonTypes::getBlockPosition($in);
		$this->face = VarInt::readSignedInt($in);
		$this->hotbarSlot = VarInt::readSignedInt($in);
		$this->itemInHand = CommonTypes::getItemStackWrapper($in);
		$this->playerPosition = CommonTypes::getVector3($in);
		$this->clickPosition = CommonTypes::getVector3($in);
		$this->blockRuntimeId = VarInt::readUnsignedInt($in);
		$this->clientInteractPrediction = PredictedResult::fromPacket(VarInt::readUnsignedInt($in));
	}

	protected function encodeData(ByteBufferWriter $out) : void{
		VarInt::writeUnsignedInt($out, $this->actionType);
		VarInt::writeUnsignedInt($out, $this->triggerType->value);
		CommonTypes::putBlockPosition($out, $this->blockPosition);
		VarInt::writeSignedInt($out, $this->face);
		VarInt::writeSignedInt($out, $this->hotbarSlot);
		CommonTypes::putItemStackWrapper($out, $this->itemInHand);
		CommonTypes::putVector3($out, $this->playerPosition);
		CommonTypes::putVector3($out, $this->clickPosition);
		VarInt::writeUnsignedInt($out, $this->blockRuntimeId);
		VarInt::writeUnsignedInt($out, $this->clientInteractPrediction->value);
	}

	/**
	 * @param NetworkInventoryAction[] $actions
	 */
	public static function new(array $actions, int $actionType, TriggerType $triggerType, BlockPosition $blockPosition, int $face, int $hotbarSlot, ItemStackWrapper $itemInHand, Vector3 $playerPosition, Vector3 $clickPosition, int $blockRuntimeId, PredictedResult $clientInteractPrediction) : self{
		$result = new self;
		$result->actions = $actions;
		$result->actionType = $actionType;
		$result->triggerType = $triggerType;
		$result->blockPosition = $blockPosition;
		$result->face = $face;
		$result->hotbarSlot = $hotbarSlot;
		$result->itemInHand = $itemInHand;
		$result->playerPosition = $playerPosition;
		$result->clickPosition = $clickPosition;
		$result->blockRuntimeId = $blockRuntimeId;
		$result->clientInteractPrediction = $clientInteractPrediction;
		return $result;
	}
}
