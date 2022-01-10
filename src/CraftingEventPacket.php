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
use Ramsey\Uuid\UuidInterface;
use function count;

class CraftingEventPacket extends DataPacket implements ServerboundPacket{
	public const NETWORK_ID = ProtocolInfo::CRAFTING_EVENT_PACKET;

	public int $windowId;
	public int $windowType;
	public UuidInterface $recipeUUID;
	/** @var ItemStackWrapper[] */
	public array $input = [];
	/** @var ItemStackWrapper[] */
	public array $output = [];

	/**
	 * @generate-create-func
	 * @param ItemStackWrapper[] $input
	 * @param ItemStackWrapper[] $output
	 */
	public static function create(int $windowId, int $windowType, UuidInterface $recipeUUID, array $input, array $output) : self{
		$result = new self;
		$result->windowId = $windowId;
		$result->windowType = $windowType;
		$result->recipeUUID = $recipeUUID;
		$result->input = $input;
		$result->output = $output;
		return $result;
	}

	protected function decodePayload(PacketSerializer $in) : void{
		$this->windowId = $in->getByte();
		$this->windowType = $in->getVarInt();
		$this->recipeUUID = $in->getUUID();

		$size = $in->getUnsignedVarInt();
		for($i = 0; $i < $size and $i < 128; ++$i){
			$this->input[] = ItemStackWrapper::read($in);
		}

		$size = $in->getUnsignedVarInt();
		for($i = 0; $i < $size and $i < 128; ++$i){
			$this->output[] = ItemStackWrapper::read($in);
		}
	}

	protected function encodePayload(PacketSerializer $out) : void{
		$out->putByte($this->windowId);
		$out->putVarInt($this->windowType);
		$out->putUUID($this->recipeUUID);

		$out->putUnsignedVarInt(count($this->input));
		foreach($this->input as $item){
			$item->write($out);
		}

		$out->putUnsignedVarInt(count($this->output));
		foreach($this->output as $item){
			$item->write($out);
		}
	}

	public function handle(PacketHandlerInterface $handler) : bool{
		return $handler->handleCraftingEvent($this);
	}
}
