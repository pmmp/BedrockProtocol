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
use pocketmine\network\mcpe\protocol\types\inventory\ItemStackWrapper;

class MobArmorEquipmentPacket extends DataPacket implements ClientboundPacket, ServerboundPacket{
	public const NETWORK_ID = ProtocolInfo::MOB_ARMOR_EQUIPMENT_PACKET;

	public int $actorRuntimeId;

	//this intentionally doesn't use an array because we don't want any implicit dependencies on internal order
	public ItemStackWrapper $head;
	public ItemStackWrapper $chest;
	public ItemStackWrapper $legs;
	public ItemStackWrapper $feet;

	/**
	 * @generate-create-func
	 */
	public static function create(int $actorRuntimeId, ItemStackWrapper $head, ItemStackWrapper $chest, ItemStackWrapper $legs, ItemStackWrapper $feet) : self{
		$result = new self;
		$result->actorRuntimeId = $actorRuntimeId;
		$result->head = $head;
		$result->chest = $chest;
		$result->legs = $legs;
		$result->feet = $feet;
		return $result;
	}

	protected function decodePayload(PacketSerializer $in) : void{
		$this->actorRuntimeId = $in->getActorRuntimeId();
		$this->head = ItemStackWrapper::read($in);
		$this->chest = ItemStackWrapper::read($in);
		$this->legs = ItemStackWrapper::read($in);
		$this->feet = ItemStackWrapper::read($in);
	}

	protected function encodePayload(PacketSerializer $out) : void{
		$out->putActorRuntimeId($this->actorRuntimeId);
		$this->head->write($out);
		$this->chest->write($out);
		$this->legs->write($out);
		$this->feet->write($out);
	}

	public function handle(PacketHandlerInterface $handler) : bool{
		return $handler->handleMobArmorEquipment($this);
	}
}
