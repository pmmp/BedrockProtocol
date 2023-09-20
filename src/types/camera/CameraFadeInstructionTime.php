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

use pocketmine\network\mcpe\protocol\serializer\PacketSerializer;

final class CameraFadeInstructionTime{

	public function __construct(
		private float $fadeInTime,
		private float $stayTime,
		private float $fadeOutTime
	){}

	public function getFadeInTime() : float{ return $this->fadeInTime; }

	public function getStayTime() : float{ return $this->stayTime; }

	public function getFadeOutTime() : float{ return $this->fadeOutTime; }

	public static function read(PacketSerializer $in) : self{
		$fadeInTime = $in->getLFloat();
		$stayTime = $in->getLFloat();
		$fadeOutTime = $in->getLFloat();
		return new self($fadeInTime, $stayTime, $fadeOutTime);
	}

	public function write(PacketSerializer $out) : void{
		$out->putLFloat($this->fadeInTime);
		$out->putLFloat($this->stayTime);
		$out->putLFloat($this->fadeOutTime);
	}
}
