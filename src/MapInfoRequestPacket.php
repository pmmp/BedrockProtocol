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

	protected function decodePayload(PacketSerializer $in) : void{
		$this->mapId = $in->getActorUniqueId();

		$this->clientPixels = [];
		$count = $in->getLInt();
		if($count > MapImage::MAX_HEIGHT * MapImage::MAX_WIDTH){
			throw new PacketDecodeException("Too many pixels");
		}
		for($i = 0; $i < $count; $i++){
			$this->clientPixels[] = MapInfoRequestPacketClientPixel::read($in);
		}
	}

	protected function encodePayload(PacketSerializer $out) : void{
		$out->putActorUniqueId($this->mapId);

		$out->putLInt(count($this->clientPixels));
		foreach($this->clientPixels as $pixel){
			$pixel->write($out);
		}
	}

	public function handle(PacketHandlerInterface $handler) : bool{
		return $handler->handleMapInfoRequest($this);
	}
}
