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
use pmmp\encoding\LE;
use pocketmine\network\mcpe\protocol\serializer\CommonTypes;
use pocketmine\network\mcpe\protocol\types\MapImage;
use pocketmine\network\mcpe\protocol\types\MapInfoRequestPacketClientPixel;
use function count;

class MapInfoRequestPacket extends DataPacket implements ServerboundPacket{
	public const NETWORK_ID = ProtocolInfo::MAP_INFO_REQUEST_PACKET;

	public int $mapId;
	/** @var MapInfoRequestPacketClientPixel[] */
	public array $clientPixels = [];

	/**
	 * @generate-create-func
	 * @param MapInfoRequestPacketClientPixel[] $clientPixels
	 */
	public static function create(int $mapId, array $clientPixels) : self{
		$result = new self;
		$result->mapId = $mapId;
		$result->clientPixels = $clientPixels;
		return $result;
	}

	protected function decodePayload(ByteBufferReader $in) : void{
		$this->mapId = CommonTypes::getActorUniqueId($in);

		$this->clientPixels = [];
		$count = LE::readUnsignedInt($in);
		if($count > MapImage::MAX_HEIGHT * MapImage::MAX_WIDTH){
			throw new PacketDecodeException("Too many pixels");
		}
		for($i = 0; $i < $count; $i++){
			$this->clientPixels[] = MapInfoRequestPacketClientPixel::read($in);
		}
	}

	protected function encodePayload(ByteBufferWriter $out) : void{
		CommonTypes::putActorUniqueId($out, $this->mapId);

		LE::writeUnsignedInt($out, count($this->clientPixels));
		foreach($this->clientPixels as $pixel){
			$pixel->write($out);
		}
	}

	public function handle(PacketHandlerInterface $handler) : bool{
		return $handler->handleMapInfoRequest($this);
	}
}
