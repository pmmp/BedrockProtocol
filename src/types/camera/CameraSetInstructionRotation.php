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

final class CameraSetInstructionRotation{

	public function __construct(
		private float $pitch,
		private float $yaw,
	){}

	public function getPitch() : float{ return $this->pitch; }

	public function getYaw() : float{ return $this->yaw; }

	public static function read(ByteBufferReader $in) : self{
		$pitch = LE::readFloat($in);
		$yaw = LE::readFloat($in);
		return new self($pitch, $yaw);
	}

	public function write(ByteBufferWriter $out) : void{
		LE::writeFloat($out, $this->pitch);
		LE::writeFloat($out, $this->yaw);
	}
}
