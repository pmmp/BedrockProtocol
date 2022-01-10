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

class ContainerSetDataPacket extends DataPacket implements ClientboundPacket{
	public const NETWORK_ID = ProtocolInfo::CONTAINER_SET_DATA_PACKET;

	public const PROPERTY_FURNACE_SMELT_PROGRESS = 0;
	public const PROPERTY_FURNACE_REMAINING_FUEL_TIME = 1;
	public const PROPERTY_FURNACE_MAX_FUEL_TIME = 2;
	public const PROPERTY_FURNACE_STORED_XP = 3;
	public const PROPERTY_FURNACE_FUEL_AUX = 4;

	public const PROPERTY_BREWING_STAND_BREW_TIME = 0;
	public const PROPERTY_BREWING_STAND_FUEL_AMOUNT = 1;
	public const PROPERTY_BREWING_STAND_FUEL_TOTAL = 2;

	public int $windowId;
	public int $property;
	public int $value;

	/**
	 * @generate-create-func
	 */
	public static function create(int $windowId, int $property, int $value) : self{
		$result = new self;
		$result->windowId = $windowId;
		$result->property = $property;
		$result->value = $value;
		return $result;
	}

	protected function decodePayload(PacketSerializer $in) : void{
		$this->windowId = $in->getByte();
		$this->property = $in->getVarInt();
		$this->value = $in->getVarInt();
	}

	protected function encodePayload(PacketSerializer $out) : void{
		$out->putByte($this->windowId);
		$out->putVarInt($this->property);
		$out->putVarInt($this->value);
	}

	public function handle(PacketHandlerInterface $handler) : bool{
		return $handler->handleContainerSetData($this);
	}
}
