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
use pocketmine\network\mcpe\protocol\types\CacheableNbt;
use pocketmine\network\mcpe\protocol\types\inventory\WindowTypes;

class UpdateTradePacket extends DataPacket implements ClientboundPacket{
	public const NETWORK_ID = ProtocolInfo::UPDATE_TRADE_PACKET;

	public int $windowId;
	public int $windowType = WindowTypes::TRADING; //Mojang hardcoded this -_-
	public int $windowSlotCount = 0; //useless, seems to be part of a standard container header
	public int $tradeTier;
	public int $traderActorUniqueId;
	public int $playerActorUniqueId;
	public string $displayName;
	public bool $isV2Trading;
	public bool $isEconomyTrading;
	/** @phpstan-var CacheableNbt<\pocketmine\nbt\tag\CompoundTag> */
	public CacheableNbt $offers;

	/**
	 * @generate-create-func
	 * @phpstan-param CacheableNbt<\pocketmine\nbt\tag\CompoundTag> $offers
	 */
	public static function create(
		int $windowId,
		int $windowType,
		int $windowSlotCount,
		int $tradeTier,
		int $traderActorUniqueId,
		int $playerActorUniqueId,
		string $displayName,
		bool $isV2Trading,
		bool $isEconomyTrading,
		CacheableNbt $offers,
	) : self{
		$result = new self;
		$result->windowId = $windowId;
		$result->windowType = $windowType;
		$result->windowSlotCount = $windowSlotCount;
		$result->tradeTier = $tradeTier;
		$result->traderActorUniqueId = $traderActorUniqueId;
		$result->playerActorUniqueId = $playerActorUniqueId;
		$result->displayName = $displayName;
		$result->isV2Trading = $isV2Trading;
		$result->isEconomyTrading = $isEconomyTrading;
		$result->offers = $offers;
		return $result;
	}

	protected function decodePayload(PacketSerializer $in) : void{
		$this->windowId = $in->getByte();
		$this->windowType = $in->getByte();
		$this->windowSlotCount = $in->getVarInt();
		$this->tradeTier = $in->getVarInt();
		$this->traderActorUniqueId = $in->getActorUniqueId();
		$this->playerActorUniqueId = $in->getActorUniqueId();
		$this->displayName = $in->getString();
		$this->isV2Trading = $in->getBool();
		$this->isEconomyTrading = $in->getBool();
		$this->offers = new CacheableNbt($in->getNbtCompoundRoot());
	}

	protected function encodePayload(PacketSerializer $out) : void{
		$out->putByte($this->windowId);
		$out->putByte($this->windowType);
		$out->putVarInt($this->windowSlotCount);
		$out->putVarInt($this->tradeTier);
		$out->putActorUniqueId($this->traderActorUniqueId);
		$out->putActorUniqueId($this->playerActorUniqueId);
		$out->putString($this->displayName);
		$out->putBool($this->isV2Trading);
		$out->putBool($this->isEconomyTrading);
		$out->put($this->offers->getEncodedNbt());
	}

	public function handle(PacketHandlerInterface $handler) : bool{
		return $handler->handleUpdateTrade($this);
	}
}
