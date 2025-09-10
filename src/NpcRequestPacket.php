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

class NpcRequestPacket extends DataPacket implements ServerboundPacket{
	public const NETWORK_ID = ProtocolInfo::NPC_REQUEST_PACKET;

	public const REQUEST_SET_ACTIONS = 0;
	public const REQUEST_EXECUTE_ACTION = 1;
	public const REQUEST_EXECUTE_CLOSING_COMMANDS = 2;
	public const REQUEST_SET_NAME = 3;
	public const REQUEST_SET_SKIN = 4;
	public const REQUEST_SET_INTERACTION_TEXT = 5;
	public const REQUEST_EXECUTE_OPENING_COMMANDS = 6;

	public int $actorRuntimeId;
	public int $requestType;
	public string $commandString;
	public int $actionIndex;
	public string $sceneName;

	/**
	 * @generate-create-func
	 */
	public static function create(int $actorRuntimeId, int $requestType, string $commandString, int $actionIndex, string $sceneName) : self{
		$result = new self;
		$result->actorRuntimeId = $actorRuntimeId;
		$result->requestType = $requestType;
		$result->commandString = $commandString;
		$result->actionIndex = $actionIndex;
		$result->sceneName = $sceneName;
		return $result;
	}

	protected function decodePayload(ByteBufferReader $in) : void{
		$this->actorRuntimeId = CommonTypes::getActorRuntimeId($in);
		$this->requestType = Byte::readUnsigned($in);
		$this->commandString = CommonTypes::getString($in);
		$this->actionIndex = Byte::readUnsigned($in);
		$this->sceneName = CommonTypes::getString($in);
	}

	protected function encodePayload(ByteBufferWriter $out) : void{
		CommonTypes::putActorRuntimeId($out, $this->actorRuntimeId);
		Byte::writeUnsigned($out, $this->requestType);
		CommonTypes::putString($out, $this->commandString);
		Byte::writeUnsigned($out, $this->actionIndex);
		CommonTypes::putString($out, $this->sceneName);
	}

	public function handle(PacketHandlerInterface $handler) : bool{
		return $handler->handleNpcRequest($this);
	}
}
