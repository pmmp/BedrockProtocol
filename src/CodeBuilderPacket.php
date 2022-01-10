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

class CodeBuilderPacket extends DataPacket implements ClientboundPacket{
	public const NETWORK_ID = ProtocolInfo::CODE_BUILDER_PACKET;

	private string $url;
	private bool $openCodeBuilder;

	/**
	 * @generate-create-func
	 */
	public static function create(string $url, bool $openCodeBuilder) : self{
		$result = new self;
		$result->url = $url;
		$result->openCodeBuilder = $openCodeBuilder;
		return $result;
	}

	public function getUrl() : string{
		return $this->url;
	}

	public function openCodeBuilder() : bool{
		return $this->openCodeBuilder;
	}

	protected function decodePayload(PacketSerializer $in) : void{
		$this->url = $in->getString();
		$this->openCodeBuilder = $in->getBool();
	}

	protected function encodePayload(PacketSerializer $out) : void{
		$out->putString($this->url);
		$out->putBool($this->openCodeBuilder);
	}

	public function handle(PacketHandlerInterface $handler) : bool{
		return $handler->handleCodeBuilder($this);
	}
}
