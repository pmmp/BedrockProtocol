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

class FilterTextPacket extends DataPacket implements ClientboundPacket, ServerboundPacket{
	public const NETWORK_ID = ProtocolInfo::FILTER_TEXT_PACKET;

	private string $text;
	private bool $fromServer;

	/**
	 * @generate-create-func
	 */
	public static function create(string $text, bool $fromServer) : self{
		$result = new self;
		$result->text = $text;
		$result->fromServer = $fromServer;
		return $result;
	}

	public function getText() : string{ return $this->text; }

	public function isFromServer() : bool{ return $this->fromServer; }

	protected function decodePayload(PacketSerializer $in) : void{
		$this->text = $in->getString();
		$this->fromServer = $in->getBool();
	}

	protected function encodePayload(PacketSerializer $out) : void{
		$out->putString($this->text);
		$out->putBool($this->fromServer);
	}

	public function handle(PacketHandlerInterface $handler) : bool{
		return $handler->handleFilterText($this);
	}
}
