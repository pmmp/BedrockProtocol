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

namespace pocketmine\network\mcpe\protocol\types\camera;

use pocketmine\math\Vector3;
use pocketmine\network\mcpe\protocol\serializer\PacketSerializer;

final class CameraSetInstruction{

	public function __construct(
		private ?CameraSetInstructionEase $ease,
		private ?Vector3 $cameraPosition,
		private ?CameraSetInstructionRotation $rotation,
		private ?Vector3 $facingPosition
	){}

	public function getEase() : ?CameraSetInstructionEase{ return $this->ease; }

	public function getCameraPosition() : ?Vector3{ return $this->cameraPosition; }

	public function getRotation() : ?CameraSetInstructionRotation{ return $this->rotation; }

	public function getFacingPosition() : ?Vector3{ return $this->facingPosition; }

	public static function read(PacketSerializer $in) : self{
		$ease = $in->readOptional(fn() => CameraSetInstructionEase::read($in));
		$cameraPosition = $in->readOptional(fn() => $in->getVector3());
		$rotation = $in->readOptional(fn() => CameraSetInstructionRotation::read($in));
		$facingPosition = $in->readOptional(fn() => $in->getVector3());

		return new self(
			$ease,
			$cameraPosition,
			$rotation,
			$facingPosition
		);
	}

	public function write(PacketSerializer $out) : void{
		$out->writeOptional($this->ease, fn(CameraSetInstructionEase $v) => $v->write($out));
		$out->writeOptional($this->cameraPosition, fn(Vector3 $v) => $out->putVector3($v));
		$out->writeOptional($this->rotation, fn(CameraSetInstructionRotation $v) => $v->write($out));
		$out->writeOptional($this->facingPosition, fn(Vector3 $v) => $out->putVector3($v));
	}
}
