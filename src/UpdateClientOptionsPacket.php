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
use pocketmine\network\mcpe\protocol\serializer\CommonTypes;
use pocketmine\network\mcpe\protocol\types\GraphicsMode;

class UpdateClientOptionsPacket extends DataPacket implements ServerboundPacket{
	public const NETWORK_ID = ProtocolInfo::UPDATE_CLIENT_OPTIONS_PACKET;

	private ?GraphicsMode $graphicsMode;
	private ?bool $filterProfanityChange;

	/**
	 * @generate-create-func
	 */
	public static function create(?GraphicsMode $graphicsMode, ?bool $filterProfanityChange) : self{
		$result = new self;
		$result->graphicsMode = $graphicsMode;
		$result->filterProfanityChange = $filterProfanityChange;
		return $result;
	}

	public function getGraphicsMode() : ?GraphicsMode{ return $this->graphicsMode; }

	public function getFilterProfanityChange() : ?bool{ return $this->filterProfanityChange; }

	protected function decodePayload(ByteBufferReader $in) : void{
		$this->graphicsMode = CommonTypes::readOptional($in, fn() => GraphicsMode::fromPacket(Byte::readUnsigned($in)));
		$this->filterProfanityChange = CommonTypes::readOptional($in, CommonTypes::getBool(...));
	}

	protected function encodePayload(ByteBufferWriter $out) : void{
		CommonTypes::writeOptional($out, $this->graphicsMode, fn(ByteBufferWriter $out, GraphicsMode $v) => Byte::writeUnsigned($out, $v->value));
		CommonTypes::writeOptional($out, $this->filterProfanityChange, CommonTypes::putBool(...));
	}

	public function handle(PacketHandlerInterface $handler) : bool{
		return $handler->handleUpdateClientOptions($this);
	}
}
