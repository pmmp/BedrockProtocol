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

final class CameraFadeInstructionColor{

	public function __construct(
		private float $red,
		private float $green,
		private float $blue,
	){}

	public function getRed() : float{ return $this->red; }

	public function getGreen() : float{ return $this->green; }

	public function getBlue() : float{ return $this->blue; }

	public static function read(PacketSerializer $in) : self{
		$red = $in->getLFloat();
		$green = $in->getLFloat();
		$blue = $in->getLFloat();
		return new self($red, $green, $blue);
	}

	public function write(PacketSerializer $out) : void{
		$out->putLFloat($this->red);
		$out->putLFloat($this->green);
		$out->putLFloat($this->blue);
	}
}
