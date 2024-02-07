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
use pocketmine\network\mcpe\protocol\types\camera\CameraFadeInstructionColor as Color;
use pocketmine\network\mcpe\protocol\types\camera\CameraFadeInstructionTime as Time;

final class CameraFadeInstruction{

	public function __construct(
		private ?Time $time,
		private ?Color $color,
	){}

	public function getTime() : ?Time{ return $this->time; }

	public function getColor() : ?Color{ return $this->color; }

	public static function read(PacketSerializer $in) : self{
		$time = $in->readOptional(fn() => Time::read($in));
		$color = $in->readOptional(fn() => Color::read($in));
		return new self(
			$time,
			$color
		);
	}

	public function write(PacketSerializer $out) : void{
		$out->writeOptional($this->time, fn(Time $v) => $v->write($out));
		$out->writeOptional($this->color, fn(Color $v) => $v->write($out));
	}
}
