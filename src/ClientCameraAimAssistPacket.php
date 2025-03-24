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
use pocketmine\network\mcpe\protocol\types\camera\CameraAimAssistActionType;

class ClientCameraAimAssistPacket extends DataPacket implements ServerboundPacket{
	public const NETWORK_ID = ProtocolInfo::CLIENT_CAMERA_AIM_ASSIST_PACKET;

	private string $presetId;
	private CameraAimAssistActionType $actionType;
	private bool $allowAimAssist;

	/**
	 * @generate-create-func
	 */
	public static function create(string $presetId, CameraAimAssistActionType $actionType, bool $allowAimAssist) : self{
		$result = new self;
		$result->presetId = $presetId;
		$result->actionType = $actionType;
		$result->allowAimAssist = $allowAimAssist;
		return $result;
	}

	public function getPresetId() : string{ return $this->presetId; }

	public function getActionType() : CameraAimAssistActionType{ return $this->actionType; }

	public function getAllowAimAssist() : bool{ return $this->allowAimAssist; }

	protected function decodePayload(PacketSerializer $in) : void{
		$this->presetId = $in->getString();
		$this->actionType = CameraAimAssistActionType::fromPacket($in->getByte());
		$this->allowAimAssist = $in->getBool();
	}

	protected function encodePayload(PacketSerializer $out) : void{
		$out->putString($this->presetId);
		$out->putByte($this->actionType->value);
		$out->putBool($this->allowAimAssist);
	}

	public function handle(PacketHandlerInterface $handler) : bool{
		return $handler->handleClientCameraAimAssist($this);
	}
}
