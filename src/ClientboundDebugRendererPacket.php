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
use pocketmine\network\mcpe\protocol\types\DebugMarkerData;

class ClientboundDebugRendererPacket extends DataPacket implements ClientboundPacket{
	public const NETWORK_ID = ProtocolInfo::CLIENTBOUND_DEBUG_RENDERER_PACKET;

	public const TYPE_CLEAR = "cleardebugmarkers";
	public const TYPE_ADD_CUBE = "adddebugmarkercube";

	private string $type;
	private ?DebugMarkerData $data = null;

	private static function base(string $type) : self{
		$result = new self;
		$result->type = $type;
		return $result;
	}

	public static function clear() : self{ return self::base(self::TYPE_CLEAR); }

	public static function addCube(DebugMarkerData $data) : self{
		$result = self::base(self::TYPE_ADD_CUBE);
		$result->data = $data;
		return $result;
	}

	public function getType() : string{ return $this->type; }

	public function getData() : ?DebugMarkerData{ return $this->data; }

	protected function decodePayload(ByteBufferReader $in) : void{
		$this->type = CommonTypes::getString($in);
		$this->data = CommonTypes::readOptional($in, DebugMarkerData::read(...));
	}

	protected function encodePayload(ByteBufferWriter $out) : void{
		CommonTypes::putString($out, $this->type);
		CommonTypes::writeOptional($out, $this->data, fn(ByteBufferWriter $out, DebugMarkerData $data) => $data->write($out));
	}

	public function handle(PacketHandlerInterface $handler) : bool{
		return $handler->handleClientboundDebugRenderer($this);
	}
}
