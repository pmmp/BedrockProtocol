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
use pocketmine\network\mcpe\protocol\types\camera\CameraFadeInstruction;
use pocketmine\network\mcpe\protocol\types\camera\CameraFovInstruction;
use pocketmine\network\mcpe\protocol\types\camera\CameraSetInstruction;
use pocketmine\network\mcpe\protocol\types\camera\CameraSplineInstruction;
use pocketmine\network\mcpe\protocol\types\camera\CameraTargetInstruction;

class CameraInstructionPacket extends DataPacket implements ClientboundPacket{
	public const NETWORK_ID = ProtocolInfo::CAMERA_INSTRUCTION_PACKET;

	private ?CameraSetInstruction $set;
	private ?bool $clear;
	private ?CameraFadeInstruction $fade;
	private ?CameraTargetInstruction $target;
	private ?bool $removeTarget;
	private ?CameraFovInstruction $fieldOfView;
	private ?CameraSplineInstruction $spline;
	private ?int $attachToEntity;
	private ?bool $detachFromEntity;

	/**
	 * @generate-create-func
	 */
	public static function create(
		?CameraSetInstruction $set,
		?bool $clear,
		?CameraFadeInstruction $fade,
		?CameraTargetInstruction $target,
		?bool $removeTarget,
		?CameraFovInstruction $fieldOfView,
		?CameraSplineInstruction $spline,
		?int $attachToEntity,
		?bool $detachFromEntity,
	) : self{
		$result = new self;
		$result->set = $set;
		$result->clear = $clear;
		$result->fade = $fade;
		$result->target = $target;
		$result->removeTarget = $removeTarget;
		$result->fieldOfView = $fieldOfView;
		$result->spline = $spline;
		$result->attachToEntity = $attachToEntity;
		$result->detachFromEntity = $detachFromEntity;
		return $result;
	}

	public function getSet() : ?CameraSetInstruction{ return $this->set; }

	public function getClear() : ?bool{ return $this->clear; }

	public function getFade() : ?CameraFadeInstruction{ return $this->fade; }

	public function getTarget() : ?CameraTargetInstruction{ return $this->target; }

	public function getRemoveTarget() : ?bool{ return $this->removeTarget; }

	public function getFieldOfView() : ?CameraFovInstruction{ return $this->fieldOfView; }

	public function getSpline() : ?CameraSplineInstruction{ return $this->spline; }

	public function getAttachToEntity() : ?int{ return $this->attachToEntity; }

	public function getDetachFromEntity() : ?bool{ return $this->detachFromEntity; }

	protected function decodePayload(ByteBufferReader $in) : void{
		$this->set = CommonTypes::readOptional($in, CameraSetInstruction::read(...));
		$this->clear = CommonTypes::readOptional($in, CommonTypes::getBool(...));
		$this->fade = CommonTypes::readOptional($in, CameraFadeInstruction::read(...));
		$this->target = CommonTypes::readOptional($in, CameraTargetInstruction::read(...));
		$this->removeTarget = CommonTypes::readOptional($in, CommonTypes::getBool(...));
		$this->fieldOfView = CommonTypes::readOptional($in, CameraFovInstruction::read(...));
		$this->spline = CommonTypes::readOptional($in, CameraSplineInstruction::read(...));
		$this->attachToEntity = CommonTypes::readOptional($in, LE::readSignedLong(...)); //WHY IS THIS NON-STANDARD?
		$this->detachFromEntity = CommonTypes::readOptional($in, CommonTypes::getBool(...));
	}

	protected function encodePayload(ByteBufferWriter $out) : void{
		CommonTypes::writeOptional($out, $this->set, fn(ByteBufferWriter $out, CameraSetInstruction $v) => $v->write($out));
		CommonTypes::writeOptional($out, $this->clear, CommonTypes::putBool(...));
		CommonTypes::writeOptional($out, $this->fade, fn(ByteBufferWriter $out, CameraFadeInstruction $v) => $v->write($out));
		CommonTypes::writeOptional($out, $this->target, fn(ByteBufferWriter $out, CameraTargetInstruction $v) => $v->write($out));
		CommonTypes::writeOptional($out, $this->removeTarget, CommonTypes::putBool(...));
		CommonTypes::writeOptional($out, $this->fieldOfView, fn(ByteBufferWriter $out, CameraFovInstruction $v) => $v->write($out));
		CommonTypes::writeOptional($out, $this->spline, fn(ByteBufferWriter $out, CameraSplineInstruction $v) => $v->write($out));
		CommonTypes::writeOptional($out, $this->attachToEntity, LE::writeSignedLong(...)); //WHY IS THIS NON-STANDARD?
		CommonTypes::writeOptional($out, $this->detachFromEntity, CommonTypes::putBool(...));
	}

	public function handle(PacketHandlerInterface $handler) : bool{
		return $handler->handleCameraInstruction($this);
	}
}
