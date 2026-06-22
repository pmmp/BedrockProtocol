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

namespace pocketmine\network\mcpe\protocol\types\shape;

use pmmp\encoding\Byte;
use pmmp\encoding\ByteBufferReader;
use pmmp\encoding\ByteBufferWriter;
use pocketmine\network\mcpe\protocol\types\GetTypeIdFromConstTrait;

final class PrimitiveShapeCircleOrSpherePayload extends PrimitiveShapePayload{
	use GetTypeIdFromConstTrait;

	public const ID = PrimitiveShapeType::PAYLOAD_TYPE_CIRCLE_OR_SPHERE;

	public function __construct(
		private int $segments,
	){}

	public function getSegments() : int{ return $this->segments; }

	public static function read(ByteBufferReader $in) : self{
		$segments = Byte::readUnsigned($in);
		return new self($segments);
	}

	public function write(ByteBufferWriter $out) : void{
		Byte::writeUnsigned($out, $this->segments);
	}
}
