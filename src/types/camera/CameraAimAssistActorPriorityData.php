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

final class CameraAimAssistActorPriorityData{

	public function __construct(
		private int $presetIndex,
		private int $categoryIndex,
		private int $actorIndex,
		private int $priority,
	){}

	public function getPresetIndex() : int { return $this->presetIndex; }

	public function getCategoryIndex() : int { return $this->categoryIndex; }

	public function getActorIndex() : int { return $this->actorIndex; }

	public function getPriority() : int { return $this->priority; }

	public static function read(ByteBufferReader $in) : self{
		$presetIndex = LE::readSignedInt($in);
		$categoryIndex = LE::readSignedInt($in);
		$actorIndex = LE::readSignedInt($in);
		$priority = LE::readSignedInt($in);
		return new self($presetIndex, $categoryIndex, $actorIndex, $priority);
	}

	public function write(ByteBufferWriter $out) : void{
		LE::writeSignedInt($out, $this->presetIndex);
		LE::writeSignedInt($out, $this->categoryIndex);
		LE::writeSignedInt($out, $this->actorIndex);
		LE::writeSignedInt($out, $this->priority);
	}
}
