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
use pocketmine\network\mcpe\protocol\types\BlockPosition;
use pocketmine\network\mcpe\protocol\types\CacheableNbt;

class BlockActorDataPacket extends DataPacket implements ClientboundPacket, ServerboundPacket{
	public const NETWORK_ID = ProtocolInfo::BLOCK_ACTOR_DATA_PACKET;

	public BlockPosition $blockPosition;
	/** @phpstan-var CacheableNbt<\pocketmine\nbt\tag\CompoundTag> */
	public CacheableNbt $nbt;

	/**
	 * @generate-create-func
	 * @phpstan-param CacheableNbt<\pocketmine\nbt\tag\CompoundTag> $nbt
	 */
	public static function create(BlockPosition $blockPosition, CacheableNbt $nbt) : self{
		$result = new self;
		$result->blockPosition = $blockPosition;
		$result->nbt = $nbt;
		return $result;
	}

	protected function decodePayload(PacketSerializer $in) : void{
		$this->blockPosition = $in->getBlockPosition();
		$this->nbt = new CacheableNbt($in->getNbtCompoundRoot());
	}

	protected function encodePayload(PacketSerializer $out) : void{
		$out->putBlockPosition($this->blockPosition);
		$out->put($this->nbt->getEncodedNbt());
	}

	public function handle(PacketHandlerInterface $handler) : bool{
		return $handler->handleBlockActorData($this);
	}
}
