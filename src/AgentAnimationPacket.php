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

class AgentAnimationPacket extends DataPacket implements ClientboundPacket{
	public const NETWORK_ID = ProtocolInfo::AGENT_ANIMATION_PACKET;

	public const TYPE_ARM_SWING = 0;
	public const TYPE_SHRUG = 1;

	private int $animationType;
	private int $actorRuntimeId;

	/**
	 * @generate-create-func
	 */
	public static function create(int $animationType, int $actorRuntimeId) : self{
		$result = new self;
		$result->animationType = $animationType;
		$result->actorRuntimeId = $actorRuntimeId;
		return $result;
	}

	public function getAnimationType() : int{ return $this->animationType; }

	public function getActorRuntimeId() : int{ return $this->actorRuntimeId; }

	protected function decodePayload(PacketSerializer $in) : void{
		$this->animationType = $in->getByte();
		$this->actorRuntimeId = $in->getActorRuntimeId();
	}

	protected function encodePayload(PacketSerializer $out) : void{
		$out->putByte($this->animationType);
		$out->putActorRuntimeId($this->actorRuntimeId);
	}

	public function handle(PacketHandlerInterface $handler) : bool{
		return $handler->handleAgentAnimation($this);
	}
}
