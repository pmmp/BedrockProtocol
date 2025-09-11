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
use pmmp\encoding\VarInt;
use pocketmine\network\mcpe\protocol\serializer\CommonTypes;

class NpcDialoguePacket extends DataPacket implements ClientboundPacket{
	public const NETWORK_ID = ProtocolInfo::NPC_DIALOGUE_PACKET;

	public const ACTION_OPEN = 0;
	public const ACTION_CLOSE = 1;

	private int $npcActorUniqueId;
	private int $actionType;
	private string $dialogue;
	private string $sceneName;
	private string $npcName;
	private string $actionJson;

	/**
	 * @generate-create-func
	 */
	public static function create(int $npcActorUniqueId, int $actionType, string $dialogue, string $sceneName, string $npcName, string $actionJson) : self{
		$result = new self;
		$result->npcActorUniqueId = $npcActorUniqueId;
		$result->actionType = $actionType;
		$result->dialogue = $dialogue;
		$result->sceneName = $sceneName;
		$result->npcName = $npcName;
		$result->actionJson = $actionJson;
		return $result;
	}

	public function getNpcActorUniqueId() : int{ return $this->npcActorUniqueId; }

	public function getActionType() : int{ return $this->actionType; }

	public function getDialogue() : string{ return $this->dialogue; }

	public function getSceneName() : string{ return $this->sceneName; }

	public function getNpcName() : string{ return $this->npcName; }

	public function getActionJson() : string{ return $this->actionJson; }

	protected function decodePayload(ByteBufferReader $in) : void{
		$this->npcActorUniqueId = LE::readSignedLong($in); //WHY NOT USING STANDARD METHODS, MOJANG
		$this->actionType = VarInt::readSignedInt($in);
		$this->dialogue = CommonTypes::getString($in);
		$this->sceneName = CommonTypes::getString($in);
		$this->npcName = CommonTypes::getString($in);
		$this->actionJson = CommonTypes::getString($in);
	}

	protected function encodePayload(ByteBufferWriter $out) : void{
		LE::writeSignedLong($out, $this->npcActorUniqueId);
		VarInt::writeSignedInt($out, $this->actionType);
		CommonTypes::putString($out, $this->dialogue);
		CommonTypes::putString($out, $this->sceneName);
		CommonTypes::putString($out, $this->npcName);
		CommonTypes::putString($out, $this->actionJson);
	}

	public function handle(PacketHandlerInterface $handler) : bool{
		return $handler->handleNpcDialogue($this);
	}
}
