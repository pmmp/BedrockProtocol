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

class AddBehaviorTreePacket extends DataPacket implements ClientboundPacket{
	public const NETWORK_ID = ProtocolInfo::ADD_BEHAVIOR_TREE_PACKET;

	public string $behaviorTreeJson;

	/**
	 * @generate-create-func
	 */
	public static function create(string $behaviorTreeJson) : self{
		$result = new self;
		$result->behaviorTreeJson = $behaviorTreeJson;
		return $result;
	}

	protected function decodePayload(ByteBufferReader $in) : void{
		$this->behaviorTreeJson = CommonTypes::getString($in);
	}

	protected function encodePayload(ByteBufferWriter $out) : void{
		CommonTypes::putString($out, $this->behaviorTreeJson);
	}

	public function handle(PacketHandlerInterface $handler) : bool{
		return $handler->handleAddBehaviorTree($this);
	}
}
