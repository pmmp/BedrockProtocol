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
use pocketmine\network\mcpe\protocol\types\inventory\InventoryLayout;
use pocketmine\network\mcpe\protocol\types\inventory\InventoryLeftTab;
use pocketmine\network\mcpe\protocol\types\inventory\InventoryRightTab;

class SetPlayerInventoryOptionsPacket extends DataPacket implements ClientboundPacket, ServerboundPacket{
	public const NETWORK_ID = ProtocolInfo::SET_PLAYER_INVENTORY_OPTIONS_PACKET;

	private InventoryLeftTab $leftTab;
	private InventoryRightTab $rightTab;
	private bool $filtering;
	private InventoryLayout $inventoryLayout;
	private InventoryLayout $craftingLayout;

	/**
	 * @generate-create-func
	 */
	public static function create(InventoryLeftTab $leftTab, InventoryRightTab $rightTab, bool $filtering, InventoryLayout $inventoryLayout, InventoryLayout $craftingLayout) : self{
		$result = new self;
		$result->leftTab = $leftTab;
		$result->rightTab = $rightTab;
		$result->filtering = $filtering;
		$result->inventoryLayout = $inventoryLayout;
		$result->craftingLayout = $craftingLayout;
		return $result;
	}

	public function getLeftTab() : InventoryLeftTab{ return $this->leftTab; }

	public function getRightTab() : InventoryRightTab{ return $this->rightTab; }

	public function isFiltering() : bool{ return $this->filtering; }

	public function getInventoryLayout() : InventoryLayout{ return $this->inventoryLayout; }

	public function getCraftingLayout() : InventoryLayout{ return $this->craftingLayout; }

	protected function decodePayload(ByteBufferReader $in) : void{
		$this->leftTab = InventoryLeftTab::fromPacket(VarInt::readSignedInt($in));
		$this->rightTab = InventoryRightTab::fromPacket(VarInt::readSignedInt($in));
		$this->filtering = CommonTypes::getBool($in);
		$this->inventoryLayout = InventoryLayout::fromPacket(VarInt::readSignedInt($in));
		$this->craftingLayout = InventoryLayout::fromPacket(VarInt::readSignedInt($in));
	}

	protected function encodePayload(ByteBufferWriter $out) : void{
		VarInt::writeSignedInt($out, $this->leftTab->value);
		VarInt::writeSignedInt($out, $this->rightTab->value);
		CommonTypes::putBool($out, $this->filtering);
		VarInt::writeSignedInt($out, $this->inventoryLayout->value);
		VarInt::writeSignedInt($out, $this->craftingLayout->value);
	}

	public function handle(PacketHandlerInterface $handler) : bool{
		return $handler->handleSetPlayerInventoryOptions($this);
	}
}
