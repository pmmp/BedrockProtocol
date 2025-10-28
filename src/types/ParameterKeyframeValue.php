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

namespace pocketmine\network\mcpe\protocol\types;

use pmmp\encoding\ByteBufferReader;
use pmmp\encoding\ByteBufferWriter;
use pmmp\encoding\LE;
use pocketmine\math\Vector3;
use pocketmine\network\mcpe\protocol\serializer\CommonTypes;

final class ParameterKeyframeValue{

	public function __construct(
		private float $time,
		private Vector3 $value,
	){}

	public function getTime() : float{ return $this->time; }

	public function getValue() : Vector3{ return $this->value; }

	public static function read(ByteBufferReader $in) : self{
		$time = LE::readFloat($in);
		$value = CommonTypes::getVector3($in);

		return new self(
			$time,
			$value
		);
	}

	public function write(ByteBufferWriter $out) : void{
		LE::writeFloat($out, $this->time);
		CommonTypes::putVector3($out, $this->value);
	}
}
