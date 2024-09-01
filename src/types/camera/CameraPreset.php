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

use pocketmine\math\Vector2;
use pocketmine\math\Vector3;
use pocketmine\network\mcpe\protocol\serializer\PacketSerializer;

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
		private ?Vector2 $viewOffset,
		private ?Vector3 $entityOffset,
		private ?float $radius,
		private ?int $audioListenerType,
		private ?bool $playerEffects
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

	public function getViewOffset() : ?Vector2{ return $this->viewOffset; }

	public function getEntityOffset() : ?Vector3{ return $this->entityOffset; }

	public function getRadius() : ?float{ return $this->radius; }

	public function getAudioListenerType() : ?int{ return $this->audioListenerType; }

	public function getPlayerEffects() : ?bool{ return $this->playerEffects; }

	public static function read(PacketSerializer $in) : self{
		$name = $in->getString();
		$parent = $in->getString();
		$xPosition = $in->readOptional($in->getLFloat(...));
		$yPosition = $in->readOptional($in->getLFloat(...));
		$zPosition = $in->readOptional($in->getLFloat(...));
		$pitch = $in->readOptional($in->getLFloat(...));
		$yaw = $in->readOptional($in->getLFloat(...));
		$rotationSpeed = $in->readOptional($in->getLFloat(...));
		$snapToTarget = $in->readOptional($in->getBool(...));
		$viewOffset = $in->readOptional($in->getVector2(...));
		$entityOffset = $in->readOptional($in->getVector3(...));
		$radius = $in->readOptional($in->getLFloat(...));
		$audioListenerType = $in->readOptional($in->getByte(...));
		$playerEffects = $in->readOptional($in->getBool(...));

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
			$viewOffset,
			$entityOffset,
			$radius,
			$audioListenerType,
			$playerEffects
		);
	}

	public function write(PacketSerializer $out) : void{
		$out->putString($this->name);
		$out->putString($this->parent);
		$out->writeOptional($this->xPosition, $out->putLFloat(...));
		$out->writeOptional($this->yPosition, $out->putLFloat(...));
		$out->writeOptional($this->zPosition, $out->putLFloat(...));
		$out->writeOptional($this->pitch, $out->putLFloat(...));
		$out->writeOptional($this->yaw, $out->putLFloat(...));
		$out->writeOptional($this->rotationSpeed, $out->putLFloat(...));
		$out->writeOptional($this->snapToTarget, $out->putBool(...));
		$out->writeOptional($this->viewOffset, $out->putVector2(...));
		$out->writeOptional($this->entityOffset, $out->putVector3(...));
		$out->writeOptional($this->radius, $out->putLFloat(...));
		$out->writeOptional($this->audioListenerType, $out->putByte(...));
		$out->writeOptional($this->playerEffects, $out->putBool(...));
	}
}
