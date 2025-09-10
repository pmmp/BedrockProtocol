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
use pmmp\encoding\LE;
use pocketmine\network\mcpe\protocol\serializer\CommonTypes;
use pocketmine\network\mcpe\protocol\types\AgentActionType;

/**
 * Used by code builder, exact purpose unclear
 */
class AgentActionEventPacket extends DataPacket implements ClientboundPacket{
	public const NETWORK_ID = ProtocolInfo::AGENT_ACTION_EVENT_PACKET;

	private string $requestId;
	/** @see AgentActionType */
	private int $action;
	private string $responseJson;

	/**
	 * @generate-create-func
	 */
	public static function create(string $requestId, int $action, string $responseJson) : self{
		$result = new self;
		$result->requestId = $requestId;
		$result->action = $action;
		$result->responseJson = $responseJson;
		return $result;
	}

	public function getRequestId() : string{ return $this->requestId; }

	/** @see AgentActionType */
	public function getAction() : int{ return $this->action; }

	public function getResponseJson() : string{ return $this->responseJson; }

	protected function decodePayload(ByteBufferReader $in) : void{
		$this->requestId = CommonTypes::getString($in);
		$this->action = LE::readUnsignedInt($in);
		$this->responseJson = CommonTypes::getString($in);
	}

	protected function encodePayload(ByteBufferWriter $out) : void{
		CommonTypes::putString($out, $this->requestId);
		LE::writeUnsignedInt($out, $this->action);
		CommonTypes::putString($out, $this->responseJson);
	}

	public function handle(PacketHandlerInterface $handler) : bool{
		return $handler->handleAgentActionEvent($this);
	}
}
