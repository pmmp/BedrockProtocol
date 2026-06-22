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
use pmmp\encoding\LE;
use pocketmine\math\Vector2;
use pocketmine\network\mcpe\protocol\serializer\CommonTypes;
use pocketmine\network\mcpe\protocol\types\GetTypeIdFromConstTrait;

final class PrimitiveShapeConePayload extends PrimitiveShapePayload{
	use GetTypeIdFromConstTrait;

	public const ID = PrimitiveShapeType::PAYLOAD_TYPE_CONE;

	public function __construct(
		private Vector2 $radii,
		private float $height,
		private int $segments,
	){}

	public function getRadii() : Vector2{ return $this->radii; }

	public function getHeight() : float{ return $this->height; }

	public function getSegments() : int{ return $this->segments; }

	public static function read(ByteBufferReader $in) : self{
		$radii = CommonTypes::getVector2($in);
		$height = LE::readFloat($in);
		$segments = Byte::readUnsigned($in);

		return new self($radii, $height, $segments);
	}

	public function write(ByteBufferWriter $out) : void{
		CommonTypes::putVector2($out, $this->radii);
		LE::writeFloat($out, $this->height);
		Byte::writeUnsigned($out, $this->segments);
	}
}
