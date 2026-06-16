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

use pmmp\encoding\Byte;
use pmmp\encoding\ByteBufferReader;
use pmmp\encoding\ByteBufferWriter;
use pmmp\encoding\LE;
use pocketmine\math\Vector2;
use pocketmine\network\mcpe\protocol\serializer\CommonTypes;

final class PrimitiveShapeCylinderPayload extends PrimitiveShapePayload{
	use GetTypeIdFromConstTrait;

	public const ID = PrimitiveShapeType::PAYLOAD_TYPE_CYLINDER;

	public function __construct(
		private Vector2 $radiusX,
		private Vector2 $radiusZ,
		private float $height,
		private int $segments,
	){}

	public function getRadiusX() : Vector2{ return $this->radiusX; }

	public function getRadiusZ() : Vector2{ return $this->radiusZ; }

	public function getHeight() : float{ return $this->height; }

	public function getSegments() : int{ return $this->segments; }

	public static function read(ByteBufferReader $in) : self{
		return new self(
			CommonTypes::getVector2($in),
			CommonTypes::getVector2($in),
			LE::readFloat($in),
			Byte::readUnsigned($in)
		);
	}

	public function write(ByteBufferWriter $out) : void{
		CommonTypes::putVector2($out, $this->radiusX);
		CommonTypes::putVector2($out, $this->radiusZ);
		LE::writeFloat($out, $this->height);
		Byte::writeUnsigned($out, $this->segments);
	}
}
