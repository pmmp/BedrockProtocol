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

class SettingsCommandPacket extends DataPacket implements ServerboundPacket{
	public const NETWORK_ID = ProtocolInfo::SETTINGS_COMMAND_PACKET;

	private string $command;
	private bool $suppressOutput;

	/**
	 * @generate-create-func
	 */
	public static function create(string $command, bool $suppressOutput) : self{
		$result = new self;
		$result->command = $command;
		$result->suppressOutput = $suppressOutput;
		return $result;
	}

	public function getCommand() : string{
		return $this->command;
	}

	public function getSuppressOutput() : bool{
		return $this->suppressOutput;
	}

	protected function decodePayload(ByteBufferReader $in) : void{
		$this->command = CommonTypes::getString($in);
		$this->suppressOutput = CommonTypes::getBool($in);
	}

	protected function encodePayload(ByteBufferWriter $out) : void{
		CommonTypes::putString($out, $this->command);
		CommonTypes::putBool($out, $this->suppressOutput);
	}

	public function handle(PacketHandlerInterface $handler) : bool{
		return $handler->handleSettingsCommand($this);
	}
}
