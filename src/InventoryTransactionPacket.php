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

namespace pocketmine\network\mcpe\protocol;

use pocketmine\network\mcpe\protocol\serializer\PacketSerializer;
use pocketmine\network\mcpe\protocol\types\inventory\InventoryTransactionChangedSlotsHack;
use pocketmine\network\mcpe\protocol\types\inventory\MismatchTransactionData;
use pocketmine\network\mcpe\protocol\types\inventory\NormalTransactionData;
use pocketmine\network\mcpe\protocol\types\inventory\ReleaseItemTransactionData;
use pocketmine\network\mcpe\protocol\types\inventory\TransactionData;
use pocketmine\network\mcpe\protocol\types\inventory\UseItemOnEntityTransactionData;
use pocketmine\network\mcpe\protocol\types\inventory\UseItemTransactionData;
use function count;

/**
 * This packet effectively crams multiple packets into one.
 */
class InventoryTransactionPacket extends DataPacket implements ClientboundPacket, ServerboundPacket{
	public const NETWORK_ID = ProtocolInfo::INVENTORY_TRANSACTION_PACKET;

	public const TYPE_NORMAL = 0;
	public const TYPE_MISMATCH = 1;
	public const TYPE_USE_ITEM = 2;
	public const TYPE_USE_ITEM_ON_ENTITY = 3;
	public const TYPE_RELEASE_ITEM = 4;

	public int $requestId;
	/** @var InventoryTransactionChangedSlotsHack[] */
	public array $requestChangedSlots;
	public TransactionData $trData;

	/**
	 * @generate-create-func
	 * @param InventoryTransactionChangedSlotsHack[] $requestChangedSlots
	 */
	public static function create(int $requestId, array $requestChangedSlots, TransactionData $trData) : self{
		$result = new self;
		$result->requestId = $requestId;
		$result->requestChangedSlots = $requestChangedSlots;
		$result->trData = $trData;
		return $result;
	}

	protected function decodePayload(PacketSerializer $in) : void{
		$this->requestId = $in->readLegacyItemStackRequestId();
		$this->requestChangedSlots = [];
		if($this->requestId !== 0){
			for($i = 0, $len = $in->getUnsignedVarInt(); $i < $len; ++$i){
				$this->requestChangedSlots[] = InventoryTransactionChangedSlotsHack::read($in);
			}
		}

		$transactionType = $in->getUnsignedVarInt();

		$this->trData = match($transactionType){
			NormalTransactionData::ID => new NormalTransactionData(),
			MismatchTransactionData::ID => new MismatchTransactionData(),
			UseItemTransactionData::ID => new UseItemTransactionData(),
			UseItemOnEntityTransactionData::ID => new UseItemOnEntityTransactionData(),
			ReleaseItemTransactionData::ID => new ReleaseItemTransactionData(),
			default => throw new PacketDecodeException("Unknown transaction type $transactionType"),
		};

		$this->trData->decode($in);
	}

	protected function encodePayload(PacketSerializer $out) : void{
		$out->writeLegacyItemStackRequestId($this->requestId);
		if($this->requestId !== 0){
			$out->putUnsignedVarInt(count($this->requestChangedSlots));
			foreach($this->requestChangedSlots as $changedSlots){
				$changedSlots->write($out);
			}
		}

		$out->putUnsignedVarInt($this->trData->getTypeId());

		$this->trData->encode($out);
	}

	public function handle(PacketHandlerInterface $handler) : bool{
		return $handler->handleInventoryTransaction($this);
	}
}
