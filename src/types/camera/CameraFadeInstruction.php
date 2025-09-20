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
use pocketmine\network\mcpe\protocol\serializer\CommonTypes;
use pocketmine\network\mcpe\protocol\types\camera\CameraFadeInstructionColor as Color;
use pocketmine\network\mcpe\protocol\types\camera\CameraFadeInstructionTime as Time;

final class CameraFadeInstruction{

	public function __construct(
		private ?Time $time,
		private ?Color $color,
	){}

	public function getTime() : ?Time{ return $this->time; }

	public function getColor() : ?Color{ return $this->color; }

	public static function read(ByteBufferReader $in) : self{
		$time = CommonTypes::readOptional($in, fn() => Time::read($in));
		$color = CommonTypes::readOptional($in, fn() => Color::read($in));
		return new self(
			$time,
			$color
		);
	}

	public function write(ByteBufferWriter $out) : void{
		CommonTypes::writeOptional($out, $this->time, fn(ByteBufferWriter $out, Time $v) => $v->write($out));
		CommonTypes::writeOptional($out, $this->color, fn(ByteBufferWriter $out, Color $v) => $v->write($out));
	}
}
