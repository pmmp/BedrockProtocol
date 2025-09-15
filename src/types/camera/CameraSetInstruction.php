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

use pmmp\encoding\ByteBufferReader;
use pmmp\encoding\ByteBufferWriter;
use pmmp\encoding\LE;
use pocketmine\math\Vector2;
use pocketmine\math\Vector3;
use pocketmine\network\mcpe\protocol\serializer\CommonTypes;

final class CameraSetInstruction{

	public function __construct(
		private int $preset,
		private ?CameraSetInstructionEase $ease,
		private ?Vector3 $cameraPosition,
		private ?CameraSetInstructionRotation $rotation,
		private ?Vector3 $facingPosition,
		private ?Vector2 $viewOffset,
		private ?Vector3 $entityOffset,
		private ?bool $default,
		private bool $ignoreStartingValuesComponent
	){}

	public function getPreset() : int{ return $this->preset; }

	public function getEase() : ?CameraSetInstructionEase{ return $this->ease; }

	public function getCameraPosition() : ?Vector3{ return $this->cameraPosition; }

	public function getRotation() : ?CameraSetInstructionRotation{ return $this->rotation; }

	public function getFacingPosition() : ?Vector3{ return $this->facingPosition; }

	public function getViewOffset() : ?Vector2{ return $this->viewOffset; }

	public function getEntityOffset() : ?Vector3{ return $this->entityOffset; }

	public function getDefault() : ?bool{ return $this->default; }

	public function isIgnoringStartingValuesComponent() : bool{ return $this->ignoreStartingValuesComponent; }

	public static function read(ByteBufferReader $in) : self{
		$preset = LE::readUnsignedInt($in);
		$ease = CommonTypes::readOptional($in, CameraSetInstructionEase::read(...));
		$cameraPosition = CommonTypes::readOptional($in, CommonTypes::getVector3(...));
		$rotation = CommonTypes::readOptional($in, CameraSetInstructionRotation::read(...));
		$facingPosition = CommonTypes::readOptional($in, CommonTypes::getVector3(...));
		$viewOffset = CommonTypes::readOptional($in, CommonTypes::getVector2(...));
		$entityOffset = CommonTypes::readOptional($in, CommonTypes::getVector3(...));
		$default = CommonTypes::readOptional($in, CommonTypes::getBool(...));
		$ignoreStartingValuesComponent = CommonTypes::getBool($in);

		return new self(
			$preset,
			$ease,
			$cameraPosition,
			$rotation,
			$facingPosition,
			$viewOffset,
			$entityOffset,
			$default,
			$ignoreStartingValuesComponent
		);
	}

	public function write(ByteBufferWriter $out) : void{
		LE::writeUnsignedInt($out, $this->preset);
		CommonTypes::writeOptional($out, $this->ease, fn(ByteBufferWriter $out, CameraSetInstructionEase $v) => $v->write($out));
		CommonTypes::writeOptional($out, $this->cameraPosition, CommonTypes::putVector3(...));
		CommonTypes::writeOptional($out, $this->rotation, fn(ByteBufferWriter $out, CameraSetInstructionRotation $v) => $v->write($out));
		CommonTypes::writeOptional($out, $this->facingPosition, CommonTypes::putVector3(...));
		CommonTypes::writeOptional($out, $this->viewOffset, CommonTypes::putVector2(...));
		CommonTypes::writeOptional($out, $this->entityOffset, CommonTypes::putVector3(...));
		CommonTypes::writeOptional($out, $this->default, CommonTypes::putBool(...));
		CommonTypes::putBool($out, $this->ignoreStartingValuesComponent);
	}
}
