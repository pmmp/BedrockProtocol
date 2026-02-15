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

class ClientboundDataDrivenUIShowScreenPacket extends DataPacket implements ClientboundPacket{
	public const NETWORK_ID = ProtocolInfo::CLIENTBOUND_DATA_DRIVEN_UI_SHOW_SCREEN_PACKET;

	private string $screenId;

	/**
	 * @generate-create-func
	 */
	public static function create(string $screenId) : self{
		$result = new self;
		$result->screenId = $screenId;
		return $result;
	}

	public function getScreenId() : string{ return $this->screenId; }

	protected function decodePayload(ByteBufferReader $in) : void{
		$this->screenId = CommonTypes::getString($in);
	}

	protected function encodePayload(ByteBufferWriter $out) : void{
		CommonTypes::putString($out, $this->screenId);
	}

	public function handle(PacketHandlerInterface $handler) : bool{
		return $handler->handleClientboundDataDrivenUIShowScreen($this);
	}
}
