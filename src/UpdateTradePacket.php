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

use pmmp\encoding\Byte;
use pmmp\encoding\ByteBufferReader;
use pmmp\encoding\ByteBufferWriter;
use pmmp\encoding\VarInt;
use pocketmine\network\mcpe\protocol\serializer\CommonTypes;
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

	protected function decodePayload(ByteBufferReader $in) : void{
		$this->windowId = Byte::readUnsigned($in);
		$this->windowType = Byte::readUnsigned($in);
		$this->windowSlotCount = VarInt::readSignedInt($in);
		$this->tradeTier = VarInt::readSignedInt($in);
		$this->traderActorUniqueId = CommonTypes::getActorUniqueId($in);
		$this->playerActorUniqueId = CommonTypes::getActorUniqueId($in);
		$this->displayName = CommonTypes::getString($in);
		$this->isV2Trading = CommonTypes::getBool($in);
		$this->isEconomyTrading = CommonTypes::getBool($in);
		$this->offers = new CacheableNbt(CommonTypes::getNbtCompoundRoot($in));
	}

	protected function encodePayload(ByteBufferWriter $out) : void{
		Byte::writeUnsigned($out, $this->windowId);
		Byte::writeUnsigned($out, $this->windowType);
		VarInt::writeSignedInt($out, $this->windowSlotCount);
		VarInt::writeSignedInt($out, $this->tradeTier);
		CommonTypes::putActorUniqueId($out, $this->traderActorUniqueId);
		CommonTypes::putActorUniqueId($out, $this->playerActorUniqueId);
		CommonTypes::putString($out, $this->displayName);
		CommonTypes::putBool($out, $this->isV2Trading);
		CommonTypes::putBool($out, $this->isEconomyTrading);
		$out->writeByteArray($this->offers->getEncodedNbt());
	}

	public function handle(PacketHandlerInterface $handler) : bool{
		return $handler->handleUpdateTrade($this);
	}
}
