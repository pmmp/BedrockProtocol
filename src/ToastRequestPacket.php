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

/**
 * Displays a toast notification on the client's screen (usually a little box at the top, like the one shown when
 * getting an Xbox Live achievement).
 */
class ToastRequestPacket extends DataPacket implements ClientboundPacket{
	public const NETWORK_ID = ProtocolInfo::TOAST_REQUEST_PACKET;

	private string $title;
	private string $body;

	/**
	 * @generate-create-func
	 */
	public static function create(string $title, string $body) : self{
		$result = new self;
		$result->title = $title;
		$result->body = $body;
		return $result;
	}

	public function getTitle() : string{ return $this->title; }

	public function getBody() : string{ return $this->body; }

	protected function decodePayload(PacketSerializer $in) : void{
		$this->title = $in->getString();
		$this->body = $in->getString();
	}

	protected function encodePayload(PacketSerializer $out) : void{
		$out->putString($this->title);
		$out->putString($this->body);
	}

	public function handle(PacketHandlerInterface $handler) : bool{
		return $handler->handleToastRequest($this);
	}
}
