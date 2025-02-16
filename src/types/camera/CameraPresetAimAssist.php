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
use pocketmine\network\mcpe\protocol\serializer\PacketSerializer;

final class CameraPresetAimAssist{

	public function __construct(
		private ?string $presetId,
		private ?CameraAimAssistTargetMode $targetMode,
		private ?Vector2 $viewAngle,
		private ?float $distance,
	){}

	public function getPresetId() : ?string{ return $this->presetId; }

	public function getTargetMode() : ?CameraAimAssistTargetMode{ return $this->targetMode; }

	public function getViewAngle() : ?Vector2{ return $this->viewAngle; }

	public function getDistance() : ?float{ return $this->distance; }

	public static function read(PacketSerializer $in) : self{
		$presetId = $in->readOptional($in->getString(...));
		$targetMode = $in->readOptional(fn() => CameraAimAssistTargetMode::fromPacket($in->getByte()));
		$viewAngle = $in->readOptional($in->getVector2(...));
		$distance = $in->readOptional($in->getLFloat(...));

		return new self(
			$presetId,
			$targetMode,
			$viewAngle,
			$distance
		);
	}

	public function write(PacketSerializer $out) : void{
		$out->writeOptional($this->presetId, $out->putString(...));
		$out->writeOptional($this->targetMode, fn(CameraAimAssistTargetMode $v) => $out->putByte($v->value));
		$out->writeOptional($this->viewAngle, $out->putVector2(...));
		$out->writeOptional($this->distance, $out->putLFloat(...));
	}
}
