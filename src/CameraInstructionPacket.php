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
use pocketmine\network\mcpe\protocol\types\camera\CameraFadeInstruction;
use pocketmine\network\mcpe\protocol\types\camera\CameraSetInstruction;
use pocketmine\network\mcpe\protocol\types\camera\CameraTargetInstruction;

class CameraInstructionPacket extends DataPacket implements ClientboundPacket{
	public const NETWORK_ID = ProtocolInfo::CAMERA_INSTRUCTION_PACKET;

	private ?CameraSetInstruction $set;
	private ?bool $clear;
	private ?CameraFadeInstruction $fade;
	private ?CameraTargetInstruction $target;
	private ?bool $removeTarget;

	/**
	 * @generate-create-func
	 */
	public static function create(?CameraSetInstruction $set, ?bool $clear, ?CameraFadeInstruction $fade, ?CameraTargetInstruction $target, ?bool $removeTarget) : self{
		$result = new self;
		$result->set = $set;
		$result->clear = $clear;
		$result->fade = $fade;
		$result->target = $target;
		$result->removeTarget = $removeTarget;
		return $result;
	}

	public function getSet() : ?CameraSetInstruction{ return $this->set; }

	public function getClear() : ?bool{ return $this->clear; }

	public function getFade() : ?CameraFadeInstruction{ return $this->fade; }

	public function getTarget() : ?CameraTargetInstruction{ return $this->target; }

	public function getRemoveTarget() : ?bool{ return $this->removeTarget; }

	protected function decodePayload(PacketSerializer $in) : void{
		$this->set = $in->readOptional(fn() => CameraSetInstruction::read($in));
		$this->clear = $in->readOptional($in->getBool(...));
		$this->fade = $in->readOptional(fn() => CameraFadeInstruction::read($in));
		$this->target = $in->readOptional(fn() => CameraTargetInstruction::read($in));
		$this->removeTarget = $in->readOptional($in->getBool(...));
	}

	protected function encodePayload(PacketSerializer $out) : void{
		$out->writeOptional($this->set, fn(CameraSetInstruction $v) => $v->write($out));
		$out->writeOptional($this->clear, $out->putBool(...));
		$out->writeOptional($this->fade, fn(CameraFadeInstruction $v) => $v->write($out));
		$out->writeOptional($this->target, fn(CameraTargetInstruction $v) => $v->write($out));
		$out->writeOptional($this->removeTarget, $out->putBool(...));
	}

	public function handle(PacketHandlerInterface $handler) : bool{
		return $handler->handleCameraInstruction($this);
	}
}
