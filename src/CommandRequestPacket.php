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
use pocketmine\network\mcpe\protocol\types\command\CommandOriginData;

class CommandRequestPacket extends DataPacket implements ServerboundPacket{
	public const NETWORK_ID = ProtocolInfo::COMMAND_REQUEST_PACKET;

	public string $command;
	public CommandOriginData $originData;
	public bool $isInternal;
	public int $version;

	/**
	 * @generate-create-func
	 */
	public static function create(string $command, CommandOriginData $originData, bool $isInternal, int $version) : self{
		$result = new self;
		$result->command = $command;
		$result->originData = $originData;
		$result->isInternal = $isInternal;
		$result->version = $version;
		return $result;
	}

	protected function decodePayload(PacketSerializer $in) : void{
		$this->command = $in->getString();
		$this->originData = $in->getCommandOriginData();
		$this->isInternal = $in->getBool();
		$this->version = $in->getVarInt();
	}

	protected function encodePayload(PacketSerializer $out) : void{
		$out->putString($this->command);
		$out->putCommandOriginData($this->originData);
		$out->putBool($this->isInternal);
		$out->putVarInt($this->version);
	}

	public function handle(PacketHandlerInterface $handler) : bool{
		return $handler->handleCommandRequest($this);
	}
}
