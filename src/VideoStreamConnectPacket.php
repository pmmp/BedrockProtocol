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

class VideoStreamConnectPacket extends DataPacket implements ClientboundPacket{
	public const NETWORK_ID = ProtocolInfo::VIDEO_STREAM_CONNECT_PACKET;

	public string $address;

	public float $screenshotFrequency;

	/**
	 * @var int Action type
	 * @phpstan-var VideoStreamAction::*
 	 */
	public int $action;

	public int $width;

	public int $height;

	/**
	 * @generate-create-func
  	 * @phpstan-var VideoStreamAction::*
	 */
	public static function create(string $address, float $screenshotFrequency, int $action, int $width, int $height) : self{
		$result = new self;
		$result->address = $address;
		$result->screenshotFrequency = $screenshotFrequency;
		$result->action = $action;
		$result->width = $width;
		$result->height = $height;
		return $result;
	}

	protected function decodePayload(PacketSerializer $in) : void{
		$this->address = $in->getString();
		$this->screenshotFrequency = $in->getLFloat();
		$this->action = $in->getUnsignedVarInt();
		$this->width = $in->getVarInt();
		$this->height = $in->getVarInt();
	}

	protected function encodePayload(PacketSerializer $out) : void{
		$out->putString($this->address);
		$out->putLFloat($this->screenshotFrequency);
		$out->putUnsignedVarInt($this->action);
		$out->putVarInt($this->width);
		$out->putVarInt($this->height);
	}

	public function handle(PacketHandlerInterface $handler) : bool{
		return $handler->handleVideoStreamConnect($this);
	}
}
