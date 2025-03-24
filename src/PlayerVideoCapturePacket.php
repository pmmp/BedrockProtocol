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

class PlayerVideoCapturePacket extends DataPacket implements ClientboundPacket{
	public const NETWORK_ID = ProtocolInfo::PLAYER_VIDEO_CAPTURE_PACKET;

	private bool $action;
	private ?int $frameRate;
	private ?string $filePrefix;

	/**
	 * @generate-create-func
	 */
	private static function create(bool $action, ?int $frameRate, ?string $filePrefix) : self{
		$result = new self;
		$result->action = $action;
		$result->frameRate = $frameRate;
		$result->filePrefix = $filePrefix;
		return $result;
	}

	public static function createStartCapture(int $frameRate, string $filePrefix) : self{
		return self::create(true, $frameRate, $filePrefix);
	}

	public static function createStopCapture() : self{
		return self::create(false, null, null);
	}

	public function getAction() : bool{ return $this->action; }

	public function getFrameRate() : ?int{ return $this->frameRate; }

	public function getFilePrefix() : ?string{ return $this->filePrefix; }

	protected function decodePayload(PacketSerializer $in) : void{
		$this->action = $in->getBool();
		if($this->action){
			$this->frameRate = $in->getLInt();
			$in->getByte();
			$in->getByte();
			$in->getByte();
			$this->filePrefix = $in->getString();
		}
	}

	protected function encodePayload(PacketSerializer $out) : void{
		$out->putBool($this->action);
		if($this->action){
			if($this->frameRate === null){ // this should never be the case
				throw new \LogicException("PlayerUpdateEntityOverridesPacket with action=true require a frame rate to be provided");
			}

			if($this->filePrefix === null){ // this should never be the case
				throw new \LogicException("PlayerUpdateEntityOverridesPacket with action=true require a file prefix to be provided");
			}

			$out->putLInt($this->frameRate);
			$out->putByte(0);
			$out->putByte(0);
			$out->putByte(0);
			$out->putString($this->filePrefix);
		}
	}

	public function handle(PacketHandlerInterface $handler) : bool{
		return $handler->handlePlayerVideoCapturePacket($this);
	}
}
