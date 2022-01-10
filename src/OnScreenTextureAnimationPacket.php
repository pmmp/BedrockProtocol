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

class OnScreenTextureAnimationPacket extends DataPacket implements ClientboundPacket{
	public const NETWORK_ID = ProtocolInfo::ON_SCREEN_TEXTURE_ANIMATION_PACKET;

	public int $effectId;

	/**
	 * @generate-create-func
	 */
	public static function create(int $effectId) : self{
		$result = new self;
		$result->effectId = $effectId;
		return $result;
	}

	protected function decodePayload(PacketSerializer $in) : void{
		$this->effectId = $in->getLInt(); //unsigned
	}

	protected function encodePayload(PacketSerializer $out) : void{
		$out->putLInt($this->effectId);
	}

	public function handle(PacketHandlerInterface $handler) : bool{
		return $handler->handleOnScreenTextureAnimation($this);
	}
}
