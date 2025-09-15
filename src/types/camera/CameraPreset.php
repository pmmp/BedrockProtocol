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

use pmmp\encoding\Byte;
use pmmp\encoding\ByteBufferReader;
use pmmp\encoding\ByteBufferWriter;
use pmmp\encoding\LE;
use pocketmine\math\Vector2;
use pocketmine\math\Vector3;
use pocketmine\network\mcpe\protocol\serializer\CommonTypes;
use pocketmine\network\mcpe\protocol\types\ControlScheme;

final class CameraPreset{
	public const AUDIO_LISTENER_TYPE_CAMERA = 0;
	public const AUDIO_LISTENER_TYPE_PLAYER = 1;

	public function __construct(
		private string $name,
		private string $parent,
		private ?float $xPosition,
		private ?float $yPosition,
		private ?float $zPosition,
		private ?float $pitch,
		private ?float $yaw,
		private ?float $rotationSpeed,
		private ?bool $snapToTarget,
		private ?Vector2 $horizontalRotationLimit,
		private ?Vector2 $verticalRotationLimit,
		private ?bool $continueTargeting,
		private ?float $blockListeningRadius,
		private ?Vector2 $viewOffset,
		private ?Vector3 $entityOffset,
		private ?float $radius,
		private ?float $yawLimitMin,
		private ?float $yawLimitMax,
		private ?int $audioListenerType,
		private ?bool $playerEffects,
		private ?CameraPresetAimAssist $aimAssist,
		private ?ControlScheme $controlScheme,
	){}

	public function getName() : string{ return $this->name; }

	public function getParent() : string{ return $this->parent; }

	public function getXPosition() : ?float{ return $this->xPosition; }

	public function getYPosition() : ?float{ return $this->yPosition; }

	public function getZPosition() : ?float{ return $this->zPosition; }

	public function getPitch() : ?float{ return $this->pitch; }

	public function getYaw() : ?float{ return $this->yaw; }

	public function getRotationSpeed() : ?float { return $this->rotationSpeed; }

	public function getSnapToTarget() : ?bool { return $this->snapToTarget; }

	public function getHorizontalRotationLimit() : ?Vector2{ return $this->horizontalRotationLimit; }

	public function getVerticalRotationLimit() : ?Vector2{ return $this->verticalRotationLimit; }

	public function getContinueTargeting() : ?bool{ return $this->continueTargeting; }

	public function getBlockListeningRadius() : ?float{ return $this->blockListeningRadius; }

	public function getViewOffset() : ?Vector2{ return $this->viewOffset; }

	public function getEntityOffset() : ?Vector3{ return $this->entityOffset; }

	public function getRadius() : ?float{ return $this->radius; }

	public function getYawLimitMin() : ?float{ return $this->yawLimitMin; }

	public function getYawLimitMax() : ?float{ return $this->yawLimitMax; }

	public function getAudioListenerType() : ?int{ return $this->audioListenerType; }

	public function getPlayerEffects() : ?bool{ return $this->playerEffects; }

	public function getAimAssist() : ?CameraPresetAimAssist{ return $this->aimAssist; }

	public function getControlScheme() : ?ControlScheme{ return $this->controlScheme; }

	public static function read(ByteBufferReader $in) : self{
		$name = CommonTypes::getString($in);
		$parent = CommonTypes::getString($in);
		$xPosition = CommonTypes::readOptional($in, LE::readFloat(...));
		$yPosition = CommonTypes::readOptional($in, LE::readFloat(...));
		$zPosition = CommonTypes::readOptional($in, LE::readFloat(...));
		$pitch = CommonTypes::readOptional($in, LE::readFloat(...));
		$yaw = CommonTypes::readOptional($in, LE::readFloat(...));
		$rotationSpeed = CommonTypes::readOptional($in, LE::readFloat(...));
		$snapToTarget = CommonTypes::readOptional($in, CommonTypes::getBool(...));
		$horizontalRotationLimit = CommonTypes::readOptional($in, CommonTypes::getVector2(...));
		$verticalRotationLimit = CommonTypes::readOptional($in, CommonTypes::getVector2(...));
		$continueTargeting = CommonTypes::readOptional($in, CommonTypes::getBool(...));
		$blockListeningRadius = CommonTypes::readOptional($in, LE::readFloat(...));
		$viewOffset = CommonTypes::readOptional($in, CommonTypes::getVector2(...));
		$entityOffset = CommonTypes::readOptional($in, CommonTypes::getVector3(...));
		$radius = CommonTypes::readOptional($in, LE::readFloat(...));
		$yawLimitMin = CommonTypes::readOptional($in, LE::readFloat(...));
		$yawLimitMax = CommonTypes::readOptional($in, LE::readFloat(...));
		$audioListenerType = CommonTypes::readOptional($in, Byte::readUnsigned(...));
		$playerEffects = CommonTypes::readOptional($in, CommonTypes::getBool(...));
		$aimAssist = CommonTypes::readOptional($in, fn() => CameraPresetAimAssist::read($in));
		$controlScheme = CommonTypes::readOptional($in, fn() => ControlScheme::fromPacket(Byte::readUnsigned($in)));

		return new self(
			$name,
			$parent,
			$xPosition,
			$yPosition,
			$zPosition,
			$pitch,
			$yaw,
			$rotationSpeed,
			$snapToTarget,
			$horizontalRotationLimit,
			$verticalRotationLimit,
			$continueTargeting,
			$blockListeningRadius,
			$viewOffset,
			$entityOffset,
			$radius,
			$yawLimitMin,
			$yawLimitMax,
			$audioListenerType,
			$playerEffects,
			$aimAssist,
			$controlScheme
		);
	}

	public function write(ByteBufferWriter $out) : void{
		CommonTypes::putString($out, $this->name);
		CommonTypes::putString($out, $this->parent);
		CommonTypes::writeOptional($out, $this->xPosition, LE::writeFloat(...));
		CommonTypes::writeOptional($out, $this->yPosition, LE::writeFloat(...));
		CommonTypes::writeOptional($out, $this->zPosition, LE::writeFloat(...));
		CommonTypes::writeOptional($out, $this->pitch, LE::writeFloat(...));
		CommonTypes::writeOptional($out, $this->yaw, LE::writeFloat(...));
		CommonTypes::writeOptional($out, $this->rotationSpeed, LE::writeFloat(...));
		CommonTypes::writeOptional($out, $this->snapToTarget, CommonTypes::putBool(...));
		CommonTypes::writeOptional($out, $this->horizontalRotationLimit, CommonTypes::putVector2(...));
		CommonTypes::writeOptional($out, $this->verticalRotationLimit, CommonTypes::putVector2(...));
		CommonTypes::writeOptional($out, $this->continueTargeting, CommonTypes::putBool(...));
		CommonTypes::writeOptional($out, $this->blockListeningRadius, LE::writeFloat(...));
		CommonTypes::writeOptional($out, $this->viewOffset, CommonTypes::putVector2(...));
		CommonTypes::writeOptional($out, $this->entityOffset, CommonTypes::putVector3(...));
		CommonTypes::writeOptional($out, $this->radius, LE::writeFloat(...));
		CommonTypes::writeOptional($out, $this->yawLimitMin, LE::writeFloat(...));
		CommonTypes::writeOptional($out, $this->yawLimitMax, LE::writeFloat(...));
		CommonTypes::writeOptional($out, $this->audioListenerType, Byte::writeUnsigned(...));
		CommonTypes::writeOptional($out, $this->playerEffects, CommonTypes::putBool(...));
		CommonTypes::writeOptional($out, $this->aimAssist, fn(ByteBufferWriter $out, CameraPresetAimAssist $v) => $v->write($out));
		CommonTypes::writeOptional($out, $this->controlScheme, fn(ByteBufferWriter $out, ControlScheme $v) => Byte::writeUnsigned($out, $v->value));
	}
}
