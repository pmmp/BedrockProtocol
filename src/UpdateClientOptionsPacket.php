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
use pocketmine\network\mcpe\protocol\types\GraphicsMode;

class UpdateClientOptionsPacket extends DataPacket implements ServerboundPacket{
	public const NETWORK_ID = ProtocolInfo::UPDATE_CLIENT_OPTIONS_PACKET;

	private ?GraphicsMode $graphicsMode;

	/**
	 * @generate-create-func
	 */
	public static function create(?GraphicsMode $graphicsMode) : self{
		$result = new self;
		$result->graphicsMode = $graphicsMode;
		return $result;
	}

	public function getGraphicsMode() : ?GraphicsMode{ return $this->graphicsMode; }

	protected function decodePayload(PacketSerializer $in) : void{
		$this->graphicsMode = $in->readOptional(fn() => GraphicsMode::fromPacket($in->getByte()));
	}

	protected function encodePayload(PacketSerializer $out) : void{
		$out->writeOptional($this->graphicsMode, fn(GraphicsMode $v) => $out->putByte($v->value));
	}

	public function handle(PacketHandlerInterface $handler) : bool{
		return $handler->handleUpdateClientOptions($this);
	}
}
