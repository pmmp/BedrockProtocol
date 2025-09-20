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
use pocketmine\network\mcpe\protocol\types\camera\CameraFadeInstruction;
use pocketmine\network\mcpe\protocol\types\camera\CameraFovInstruction;
use pocketmine\network\mcpe\protocol\types\camera\CameraSetInstruction;
use pocketmine\network\mcpe\protocol\types\camera\CameraTargetInstruction;

class CameraInstructionPacket extends DataPacket implements ClientboundPacket{
	public const NETWORK_ID = ProtocolInfo::CAMERA_INSTRUCTION_PACKET;

	private ?CameraSetInstruction $set;
	private ?bool $clear;
	private ?CameraFadeInstruction $fade;
	private ?CameraTargetInstruction $target;
	private ?bool $removeTarget;
	private ?CameraFovInstruction $fieldOfView;

	/**
	 * @generate-create-func
	 */
	public static function create(?CameraSetInstruction $set, ?bool $clear, ?CameraFadeInstruction $fade, ?CameraTargetInstruction $target, ?bool $removeTarget, ?CameraFovInstruction $fieldOfView) : self{
		$result = new self;
		$result->set = $set;
		$result->clear = $clear;
		$result->fade = $fade;
		$result->target = $target;
		$result->removeTarget = $removeTarget;
		$result->fieldOfView = $fieldOfView;
		return $result;
	}

	public function getSet() : ?CameraSetInstruction{ return $this->set; }

	public function getClear() : ?bool{ return $this->clear; }

	public function getFade() : ?CameraFadeInstruction{ return $this->fade; }

	public function getTarget() : ?CameraTargetInstruction{ return $this->target; }

	public function getRemoveTarget() : ?bool{ return $this->removeTarget; }

	public function getFieldOfView() : ?CameraFovInstruction{ return $this->fieldOfView; }

	protected function decodePayload(ByteBufferReader $in) : void{
		$this->set = CommonTypes::readOptional($in, CameraSetInstruction::read(...));
		$this->clear = CommonTypes::readOptional($in, CommonTypes::getBool(...));
		$this->fade = CommonTypes::readOptional($in, CameraFadeInstruction::read(...));
		$this->target = CommonTypes::readOptional($in, CameraTargetInstruction::read(...));
		$this->removeTarget = CommonTypes::readOptional($in, CommonTypes::getBool(...));
		$this->fieldOfView = CommonTypes::readOptional($in, CameraFovInstruction::read(...));
	}

	protected function encodePayload(ByteBufferWriter $out) : void{
		CommonTypes::writeOptional($out, $this->set, fn(ByteBufferWriter $out, CameraSetInstruction $v) => $v->write($out));
		CommonTypes::writeOptional($out, $this->clear, CommonTypes::putBool(...));
		CommonTypes::writeOptional($out, $this->fade, fn(ByteBufferWriter $out, CameraFadeInstruction $v) => $v->write($out));
		CommonTypes::writeOptional($out, $this->target, fn(ByteBufferWriter $out, CameraTargetInstruction $v) => $v->write($out));
		CommonTypes::writeOptional($out, $this->removeTarget, CommonTypes::putBool(...));
		CommonTypes::writeOptional($out, $this->fieldOfView, fn(ByteBufferWriter $out, CameraFovInstruction $v) => $v->write($out));
	}

	public function handle(PacketHandlerInterface $handler) : bool{
		return $handler->handleCameraInstruction($this);
	}
}
