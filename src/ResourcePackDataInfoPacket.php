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
use pmmp\encoding\LE;
use pocketmine\network\mcpe\protocol\serializer\CommonTypes;
use pocketmine\network\mcpe\protocol\types\resourcepacks\ResourcePackType;

class ResourcePackDataInfoPacket extends DataPacket implements ClientboundPacket{
	public const NETWORK_ID = ProtocolInfo::RESOURCE_PACK_DATA_INFO_PACKET;

	public string $packId;
	public int $maxChunkSize;
	public int $chunkCount;
	public int $compressedPackSize;
	public string $sha256;
	public bool $isPremium = false;
	public int $packType = ResourcePackType::RESOURCES; //TODO: check the values for this

	/**
	 * @generate-create-func
	 */
	public static function create(
		string $packId,
		int $maxChunkSize,
		int $chunkCount,
		int $compressedPackSize,
		string $sha256,
		bool $isPremium,
		int $packType,
	) : self{
		$result = new self;
		$result->packId = $packId;
		$result->maxChunkSize = $maxChunkSize;
		$result->chunkCount = $chunkCount;
		$result->compressedPackSize = $compressedPackSize;
		$result->sha256 = $sha256;
		$result->isPremium = $isPremium;
		$result->packType = $packType;
		return $result;
	}

	protected function decodePayload(ByteBufferReader $in) : void{
		$this->packId = CommonTypes::getString($in);
		$this->maxChunkSize = LE::readUnsignedInt($in);
		$this->chunkCount = LE::readUnsignedInt($in);
		$this->compressedPackSize = LE::readUnsignedLong($in);
		$this->sha256 = CommonTypes::getString($in);
		$this->isPremium = CommonTypes::getBool($in);
		$this->packType = Byte::readUnsigned($in);
	}

	protected function encodePayload(ByteBufferWriter $out) : void{
		CommonTypes::putString($out, $this->packId);
		LE::writeUnsignedInt($out, $this->maxChunkSize);
		LE::writeUnsignedInt($out, $this->chunkCount);
		LE::writeUnsignedLong($out, $this->compressedPackSize);
		CommonTypes::putString($out, $this->sha256);
		CommonTypes::putBool($out, $this->isPremium);
		Byte::writeUnsigned($out, $this->packType);
	}

	public function handle(PacketHandlerInterface $handler) : bool{
		return $handler->handleResourcePackDataInfo($this);
	}
}
