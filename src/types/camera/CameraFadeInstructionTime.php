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

final class CameraFadeInstructionTime{

	public function __construct(
		private float $fadeInTime,
		private float $stayTime,
		private float $fadeOutTime
	){}

	public function getFadeInTime() : float{ return $this->fadeInTime; }

	public function getStayTime() : float{ return $this->stayTime; }

	public function getFadeOutTime() : float{ return $this->fadeOutTime; }

	public static function read(ByteBufferReader $in) : self{
		$fadeInTime = LE::readFloat($in);
		$stayTime = LE::readFloat($in);
		$fadeOutTime = LE::readFloat($in);
		return new self($fadeInTime, $stayTime, $fadeOutTime);
	}

	public function write(ByteBufferWriter $out) : void{
		LE::writeFloat($out, $this->fadeInTime);
		LE::writeFloat($out, $this->stayTime);
		LE::writeFloat($out, $this->fadeOutTime);
	}
}
