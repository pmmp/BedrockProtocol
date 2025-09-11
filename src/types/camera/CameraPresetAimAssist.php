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
use pocketmine\network\mcpe\protocol\serializer\CommonTypes;

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

	public static function read(ByteBufferReader $in) : self{
		$presetId = CommonTypes::readOptional($in, CommonTypes::getString(...));
		$targetMode = CommonTypes::readOptional($in, fn() => CameraAimAssistTargetMode::fromPacket(Byte::readUnsigned($in)));
		$viewAngle = CommonTypes::readOptional($in, CommonTypes::getVector2(...));
		$distance = CommonTypes::readOptional($in, LE::readFloat(...));

		return new self(
			$presetId,
			$targetMode,
			$viewAngle,
			$distance
		);
	}

	public function write(ByteBufferWriter $out) : void{
		CommonTypes::writeOptional($out, $this->presetId, CommonTypes::putString(...));
		CommonTypes::writeOptional($out, $this->targetMode, fn(ByteBufferWriter $out, CameraAimAssistTargetMode $v) => Byte::writeUnsigned($out, $v->value));
		CommonTypes::writeOptional($out, $this->viewAngle, CommonTypes::putVector2(...));
		CommonTypes::writeOptional($out, $this->distance, LE::writeFloat(...));
	}
}
