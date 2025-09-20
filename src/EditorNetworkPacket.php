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
use pocketmine\network\mcpe\protocol\serializer\CommonTypes;
use pocketmine\network\mcpe\protocol\types\CacheableNbt;

/**
 * Unclear purpose, not used in vanilla Bedrock. Seems to be related to a new Minecraft "editor" edition or mode.
 */
class EditorNetworkPacket extends DataPacket implements ClientboundPacket, ServerboundPacket{
	public const NETWORK_ID = ProtocolInfo::EDITOR_NETWORK_PACKET;

	private bool $isRouteToManager;
	/** @phpstan-var CacheableNbt<\pocketmine\nbt\tag\CompoundTag> */
	private CacheableNbt $payload;

	/**
	 * @generate-create-func
	 * @phpstan-param CacheableNbt<\pocketmine\nbt\tag\CompoundTag> $payload
	 */
	public static function create(bool $isRouteToManager, CacheableNbt $payload) : self{
		$result = new self;
		$result->isRouteToManager = $isRouteToManager;
		$result->payload = $payload;
		return $result;
	}

	/** @phpstan-return CacheableNbt<\pocketmine\nbt\tag\CompoundTag> */
	public function getPayload() : CacheableNbt{ return $this->payload; }

	public function isRouteToManager() : bool{ return $this->isRouteToManager; }

	protected function decodePayload(ByteBufferReader $in) : void{
		$this->isRouteToManager = CommonTypes::getBool($in);
		$this->payload = new CacheableNbt(CommonTypes::getNbtCompoundRoot($in));
	}

	protected function encodePayload(ByteBufferWriter $out) : void{
		CommonTypes::putBool($out, $this->isRouteToManager);
		$out->writeByteArray($this->payload->getEncodedNbt());
	}

	public function handle(PacketHandlerInterface $handler) : bool{
		return $handler->handleEditorNetwork($this);
	}
}
