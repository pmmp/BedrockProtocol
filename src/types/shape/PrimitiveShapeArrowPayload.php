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
use pocketmine\math\Vector3;
use pocketmine\network\mcpe\protocol\serializer\CommonTypes;
use pocketmine\network\mcpe\protocol\types\GetTypeIdFromConstTrait;

final class PrimitiveShapeArrowPayload extends PrimitiveShapePayload{
	use GetTypeIdFromConstTrait;

	public const ID = PrimitiveShapeType::PAYLOAD_TYPE_ARROW;

	public function __construct(
		private ?Vector3 $lineEndLocation,
		private ?float $arrowHeadLength,
		private ?float $arrowHeadRadius,
		private ?int $segments,
	){}

	public function getLineEndLocation() : ?Vector3{ return $this->lineEndLocation; }

	public function getArrowHeadLength() : ?float{ return $this->arrowHeadLength; }

	public function getArrowHeadRadius() : ?float{ return $this->arrowHeadRadius; }

	public function getSegments() : ?int{ return $this->segments; }

	public static function read(ByteBufferReader $in) : self{
		$lineEndLocation = CommonTypes::readOptional($in, CommonTypes::getVector3(...));
		$arrowHeadLength = CommonTypes::readOptional($in, LE::readFloat(...));
		$arrowHeadRadius = CommonTypes::readOptional($in, LE::readFloat(...));
		$segments = CommonTypes::readOptional($in, Byte::readUnsigned(...));

		return new self(
			$lineEndLocation,
			$arrowHeadLength,
			$arrowHeadRadius,
			$segments,
		);
	}

	public function write(ByteBufferWriter $out) : void{
		CommonTypes::writeOptional($out, $this->lineEndLocation, CommonTypes::putVector3(...));
		CommonTypes::writeOptional($out, $this->arrowHeadLength, LE::writeFloat(...));
		CommonTypes::writeOptional($out, $this->arrowHeadRadius, LE::writeFloat(...));
		CommonTypes::writeOptional($out, $this->segments, Byte::writeUnsigned(...));
	}
}
