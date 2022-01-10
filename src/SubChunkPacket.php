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
use pocketmine\network\mcpe\protocol\types\SubChunkPacketHeightMapInfo;
use pocketmine\network\mcpe\protocol\types\SubChunkPacketHeightMapType;

class SubChunkPacket extends DataPacket implements ClientboundPacket{
	public const NETWORK_ID = ProtocolInfo::SUB_CHUNK_PACKET;

	private int $dimension;
	private int $subChunkX;
	private int $subChunkY;
	private int $subChunkZ;
	private string $data;
	private int $requestResult;
	private ?SubChunkPacketHeightMapInfo $heightMapData = null;
	private ?int $usedBlobHash = null;

	/**
	 * @generate-create-func
	 */
	public static function create(
		int $dimension,
		int $subChunkX,
		int $subChunkY,
		int $subChunkZ,
		string $data,
		int $requestResult,
		?SubChunkPacketHeightMapInfo $heightMapData,
		?int $usedBlobHash,
	) : self{
		$result = new self;
		$result->dimension = $dimension;
		$result->subChunkX = $subChunkX;
		$result->subChunkY = $subChunkY;
		$result->subChunkZ = $subChunkZ;
		$result->data = $data;
		$result->requestResult = $requestResult;
		$result->heightMapData = $heightMapData;
		$result->usedBlobHash = $usedBlobHash;
		return $result;
	}

	public function getDimension() : int{ return $this->dimension; }

	public function getSubChunkX() : int{ return $this->subChunkX; }

	public function getSubChunkY() : int{ return $this->subChunkY; }

	public function getSubChunkZ() : int{ return $this->subChunkZ; }

	public function getData() : string{ return $this->data; }

	public function getRequestResult() : int{ return $this->requestResult; }

	public function getHeightMapData() : ?SubChunkPacketHeightMapInfo{ return $this->heightMapData; }

	public function getUsedBlobHash() : ?int{ return $this->usedBlobHash; }

	protected function decodePayload(PacketSerializer $in) : void{
		$this->dimension = $in->getVarInt();
		$this->subChunkX = $in->getVarInt();
		$this->subChunkY = $in->getVarInt();
		$this->subChunkZ = $in->getVarInt();
		$this->data = $in->getString();
		$this->requestResult = $in->getVarInt();
		$heightMapDataType = $in->getByte();
		$this->heightMapData = match($heightMapDataType){
			SubChunkPacketHeightMapType::NO_DATA => null,
			SubChunkPacketHeightMapType::DATA => SubChunkPacketHeightMapInfo::read($in),
			SubChunkPacketHeightMapType::ALL_TOO_HIGH => SubChunkPacketHeightMapInfo::allTooHigh(),
			SubChunkPacketHeightMapType::ALL_TOO_LOW => SubChunkPacketHeightMapInfo::allTooLow(),
			default => throw new PacketDecodeException("Unknown heightmap data type $heightMapDataType")
		};
		$this->usedBlobHash = $in->getBool() ? $in->getLLong() : null;
	}

	protected function encodePayload(PacketSerializer $out) : void{
		$out->putVarInt($this->dimension);
		$out->putVarInt($this->subChunkX);
		$out->putVarInt($this->subChunkY);
		$out->putVarInt($this->subChunkZ);
		$out->putString($this->data);
		$out->putVarInt($this->requestResult);
		if($this->heightMapData === null){
			$out->putByte(SubChunkPacketHeightMapType::NO_DATA);
		}elseif($this->heightMapData->isAllTooLow()){
			$out->putByte(SubChunkPacketHeightMapType::ALL_TOO_LOW);
		}elseif($this->heightMapData->isAllTooHigh()){
			$out->putByte(SubChunkPacketHeightMapType::ALL_TOO_HIGH);
		}else{
			$heightMapData = $this->heightMapData; //avoid PHPStan purity issue
			$out->putByte(SubChunkPacketHeightMapType::DATA);
			$heightMapData->write($out);
		}
		$usedBlobHash = $this->usedBlobHash;
		$out->putBool($usedBlobHash !== null);
		if($usedBlobHash !== null){
			$out->putLLong($usedBlobHash);
		}
	}

	public function handle(PacketHandlerInterface $handler) : bool{
		return $handler->handleSubChunk($this);
	}
}
