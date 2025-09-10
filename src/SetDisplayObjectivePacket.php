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

use pmmp\encoding\ByteBufferReader;
use pmmp\encoding\ByteBufferWriter;
use pmmp\encoding\VarInt;
use pocketmine\network\mcpe\protocol\serializer\CommonTypes;

class SetDisplayObjectivePacket extends DataPacket implements ClientboundPacket{
	public const NETWORK_ID = ProtocolInfo::SET_DISPLAY_OBJECTIVE_PACKET;

	public const DISPLAY_SLOT_LIST = "list";
	public const DISPLAY_SLOT_SIDEBAR = "sidebar";
	public const DISPLAY_SLOT_BELOW_NAME = "belowname";

	public const SORT_ORDER_ASCENDING = 0;
	public const SORT_ORDER_DESCENDING = 1;

	public string $displaySlot;
	public string $objectiveName;
	public string $displayName;
	public string $criteriaName;
	public int $sortOrder;

	/**
	 * @generate-create-func
	 */
	public static function create(string $displaySlot, string $objectiveName, string $displayName, string $criteriaName, int $sortOrder) : self{
		$result = new self;
		$result->displaySlot = $displaySlot;
		$result->objectiveName = $objectiveName;
		$result->displayName = $displayName;
		$result->criteriaName = $criteriaName;
		$result->sortOrder = $sortOrder;
		return $result;
	}

	protected function decodePayload(ByteBufferReader $in) : void{
		$this->displaySlot = CommonTypes::getString($in);
		$this->objectiveName = CommonTypes::getString($in);
		$this->displayName = CommonTypes::getString($in);
		$this->criteriaName = CommonTypes::getString($in);
		$this->sortOrder = VarInt::readSignedInt($in);
	}

	protected function encodePayload(ByteBufferWriter $out) : void{
		CommonTypes::putString($out, $this->displaySlot);
		CommonTypes::putString($out, $this->objectiveName);
		CommonTypes::putString($out, $this->displayName);
		CommonTypes::putString($out, $this->criteriaName);
		VarInt::writeSignedInt($out, $this->sortOrder);
	}

	public function handle(PacketHandlerInterface $handler) : bool{
		return $handler->handleSetDisplayObjective($this);
	}
}
