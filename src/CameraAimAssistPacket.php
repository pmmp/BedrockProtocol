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

use pocketmine\math\Vector2;
use pocketmine\network\mcpe\protocol\serializer\PacketSerializer;
use pocketmine\network\mcpe\protocol\types\camera\CameraAimAssistActionType;
use pocketmine\network\mcpe\protocol\types\camera\CameraAimAssistTargetMode;

class CameraAimAssistPacket extends DataPacket implements ClientboundPacket{
	public const NETWORK_ID = ProtocolInfo::CAMERA_AIM_ASSIST_PACKET;

	private string $presetId;
	private Vector2 $viewAngle;
	private float $distance;
	private CameraAimAssistTargetMode $targetMode;
	private CameraAimAssistActionType $actionType;
	private bool $showDebugRender;

	/**
	 * @generate-create-func
	 */
	public static function create(string $presetId, Vector2 $viewAngle, float $distance, CameraAimAssistTargetMode $targetMode, CameraAimAssistActionType $actionType, bool $showDebugRender) : self{
		$result = new self;
		$result->presetId = $presetId;
		$result->viewAngle = $viewAngle;
		$result->distance = $distance;
		$result->targetMode = $targetMode;
		$result->actionType = $actionType;
		$result->showDebugRender = $showDebugRender;
		return $result;
	}

	public function getPresetId() : string{ return $this->presetId; }

	public function getViewAngle() : Vector2{ return $this->viewAngle; }

	public function getDistance() : float{ return $this->distance; }

	public function getTargetMode() : CameraAimAssistTargetMode{ return $this->targetMode; }

	public function getActionType() : CameraAimAssistActionType{ return $this->actionType; }

	public function getShowDebugRender() : bool{ return $this->showDebugRender; }

	protected function decodePayload(PacketSerializer $in) : void{
		$this->presetId = $in->getString();
		$this->viewAngle = $in->getVector2();
		$this->distance = $in->getLFloat();
		$this->targetMode = CameraAimAssistTargetMode::fromPacket($in->getByte());
		$this->actionType = CameraAimAssistActionType::fromPacket($in->getByte());
		$this->showDebugRender = $in->getBool();
	}

	protected function encodePayload(PacketSerializer $out) : void{
		$out->putString($this->presetId);
		$out->putVector2($this->viewAngle);
		$out->putLFloat($this->distance);
		$out->putByte($this->targetMode->value);
		$out->putByte($this->actionType->value);
		$out->putBool($this->showDebugRender);
	}

	public function handle(PacketHandlerInterface $handler) : bool{
		return $handler->handleCameraAimAssist($this);
	}
}
