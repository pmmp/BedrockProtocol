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

use pmmp\encoding\ByteBufferReader;
use pmmp\encoding\ByteBufferWriter;
use pmmp\encoding\LE;
use pocketmine\network\mcpe\protocol\serializer\CommonTypes;
use pocketmine\network\mcpe\protocol\types\GetTypeIdFromConstTrait;

final class PrimitiveShapePyramidPayload extends PrimitiveShapePayload{
	use GetTypeIdFromConstTrait;

	public const ID = PrimitiveShapeType::PAYLOAD_TYPE_PYRAMID;

	public function __construct(
		private float $width,
		private ?float $depth,
		private float $height,
	){}

	public function getWidth() : float{ return $this->width; }

	public function getDepth() : ?float{ return $this->depth; }

	public function getHeight() : float{ return $this->height; }

	public static function read(ByteBufferReader $in) : self{
		$width = LE::readFloat($in);
		$depth = CommonTypes::readOptional($in, LE::readFloat(...));
		$height = LE::readFloat($in);

		return new self($width, $depth, $height);
	}

	public function write(ByteBufferWriter $out) : void{
		LE::writeFloat($out, $this->width);
		CommonTypes::writeOptional($out, $this->depth, LE::writeFloat(...));
		LE::writeFloat($out, $this->height);
	}
}
