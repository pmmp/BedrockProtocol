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

use pocketmine\nbt\tag\CompoundTag;
use pocketmine\network\mcpe\protocol\serializer\PacketSerializer;
use pocketmine\network\mcpe\protocol\types\CacheableNbt;

class CameraInstructionPacket extends DataPacket implements ClientboundPacket{
	public const NETWORK_ID = ProtocolInfo::CAMERA_INSTRUCTION_PACKET;

	/** @phpstan-var CacheableNbt<CompoundTag> */
	private CacheableNbt $data;

	/**
	 * @generate-create-func
	 * @phpstan-param CacheableNbt<CompoundTag> $data
	 */
	public static function create(CacheableNbt $data) : self{
		$result = new self;
		$result->data = $data;
		return $result;
	}

	/**
	 * @phpstan-return CacheableNbt<CompoundTag>
	 */
	public function getData() : CacheableNbt{ return $this->data; }

	protected function decodePayload(PacketSerializer $in) : void{
		$this->data = new CacheableNbt($in->getNbtCompoundRoot());
	}

	protected function encodePayload(PacketSerializer $out) : void{
		$out->put($this->data->getEncodedNbt());
	}

	public function handle(PacketHandlerInterface $handler) : bool{
		return $handler->handleCameraInstruction($this);
	}
}
