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

use pmmp\encoding\Byte;
use pmmp\encoding\ByteBufferReader;
use pmmp\encoding\ByteBufferWriter;
use pmmp\encoding\DataDecodeException;
use pmmp\encoding\VarInt;
use pocketmine\network\mcpe\protocol\PacketDecodeException;
use pocketmine\network\mcpe\protocol\serializer\CommonTypes;

class NetworkInventoryAction{
	public const SOURCE_CONTAINER = 0;

	public const SOURCE_GLOBAL = 1;
	public const SOURCE_WORLD = 2; //drop/pickup item entity
	public const SOURCE_CREATIVE = 3;
	public const SOURCE_TODO = 99999;

	/**
	 * Fake window IDs for the SOURCE_TODO type (99999)
	 *
	 * These identifiers are used for inventory source types which are not currently implemented server-side in MCPE.
	 * As a general rule of thumb, anything that doesn't have a permanent inventory is client-side. These types are
	 * to allow servers to track what is going on in client-side windows.
	 *
	 * Expect these to change in the future.
	 */
	public const SOURCE_TYPE_CRAFTING_RESULT = -4;
	public const SOURCE_TYPE_CRAFTING_USE_INGREDIENT = -5;

	public const SOURCE_TYPE_ANVIL_RESULT = -12;
	public const SOURCE_TYPE_ANVIL_OUTPUT = -13;

	public const SOURCE_TYPE_ENCHANT_OUTPUT = -17;

	public const SOURCE_TYPE_TRADING_INPUT_1 = -20;
	public const SOURCE_TYPE_TRADING_INPUT_2 = -21;
	public const SOURCE_TYPE_TRADING_USE_INPUTS = -22;
	public const SOURCE_TYPE_TRADING_OUTPUT = -23;

	public const SOURCE_TYPE_BEACON = -24;

	public const ACTION_MAGIC_SLOT_CREATIVE_DELETE_ITEM = 0;
	public const ACTION_MAGIC_SLOT_CREATIVE_CREATE_ITEM = 1;

	public const ACTION_MAGIC_SLOT_DROP_ITEM = 0;
	public const ACTION_MAGIC_SLOT_PICKUP_ITEM = 1;

	public int $sourceType;
	public ?int $windowId = null;
	public ?int $sourceFlags = null;
	public int $inventorySlot;
	public ItemStackWrapper $oldItem;
	public ItemStackWrapper $newItem;

	/**
	 * @return $this
	 *
	 * @throws DataDecodeException
	 * @throws PacketDecodeException
	 */
	public function readAuthInput(ByteBufferReader $in) : NetworkInventoryAction{
		$this->sourceType = VarInt::readUnsignedInt($in);

		switch($this->sourceType){
			case self::SOURCE_CONTAINER:
				$this->windowId = VarInt::readSignedInt($in);
				break;
			case self::SOURCE_WORLD:
				$this->sourceFlags = VarInt::readUnsignedInt($in);
				break;
			case self::SOURCE_CREATIVE:
				break;
			case self::SOURCE_TODO:
				$this->windowId = VarInt::readSignedInt($in);
				break;
			default:
				throw new PacketDecodeException("Unknown inventory action source type $this->sourceType");
		}

		$this->inventorySlot = VarInt::readUnsignedInt($in);
		$this->oldItem = CommonTypes::getItemStackWrapper($in);
		$this->newItem = CommonTypes::getItemStackWrapper($in);

		return $this;
	}

	public function writeAuthInput(ByteBufferWriter $out) : void{
		VarInt::writeUnsignedInt($out, $this->sourceType);

		switch($this->sourceType){
			case self::SOURCE_CONTAINER:
				if($this->windowId === null){
					throw new \LogicException("WindowID must be set for SOURCE_CONTAINER");
				}
				VarInt::writeSignedInt($out, $this->windowId);
				break;
			case self::SOURCE_WORLD:
				if($this->sourceFlags === null){
					throw new \LogicException("SourceFlags must be set for SOURCE_WORLD");
				}
				VarInt::writeUnsignedInt($out, $this->sourceFlags);
				break;
			case self::SOURCE_CREATIVE:
				break;
			case self::SOURCE_TODO:
				if($this->windowId === null){
					throw new \LogicException("WindowID must be set for SOURCE_TODO");
				}
				VarInt::writeSignedInt($out, $this->windowId);
				break;
			default:
				throw new \InvalidArgumentException("Unknown inventory action source type $this->sourceType");
		}

		VarInt::writeUnsignedInt($out, $this->inventorySlot);
		CommonTypes::putItemStackWrapper($out, $this->oldItem);
		CommonTypes::putItemStackWrapper($out, $this->newItem);
	}

	/**
	 * @return $this
	 *
	 * @throws DataDecodeException
	 * @throws PacketDecodeException
	 */
	public function readTransaction(ByteBufferReader $in) : NetworkInventoryAction{
		$this->sourceType = VarInt::readUnsignedInt($in);

		if(Byte::readUnsigned($in) !== 1){
			throw new PacketDecodeException("Inconsistent optional state for windowId");
		}
		$this->windowId = CommonTypes::readOptional($in, Byte::readSigned(...));

		if(Byte::readUnsigned($in) !== 1){
			throw new PacketDecodeException("Inconsistent optional state for sourceFlags");
		}
		$this->sourceFlags = CommonTypes::readOptional($in, VarInt::readUnsignedInt(...));

		$this->inventorySlot = VarInt::readUnsignedInt($in);
		$this->oldItem = CommonTypes::getNetworkItemStackDescriptor($in);
		$this->newItem = CommonTypes::getNetworkItemStackDescriptor($in);

		return $this;
	}

	/**
	 * @throws \InvalidArgumentException
	 */
	public function writeTransaction(ByteBufferWriter $out) : void{
		VarInt::writeUnsignedInt($out, $this->sourceType);

		Byte::writeUnsigned($out, 1);
		CommonTypes::writeOptional($out, $this->windowId, Byte::writeSigned(...));

		Byte::writeUnsigned($out, 1);
		CommonTypes::writeOptional($out, $this->sourceFlags, VarInt::writeUnsignedInt(...));

		VarInt::writeUnsignedInt($out, $this->inventorySlot);
		CommonTypes::putNetworkItemStackDescriptor($out, $this->oldItem);
		CommonTypes::putNetworkItemStackDescriptor($out, $this->newItem);
	}
}
