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
use pocketmine\math\Vector3;
use pocketmine\network\mcpe\protocol\serializer\CommonTypes;
use pocketmine\network\mcpe\protocol\types\GetTypeIdFromConstTrait;

final class PrimitiveShapeEllipsoidPayload extends PrimitiveShapePayload{
	use GetTypeIdFromConstTrait;

	public const ID = PrimitiveShapeType::PAYLOAD_TYPE_ELLIPSOID;

	public function __construct(
		private Vector3 $radii,
		private int $segmentsPerAxis,
	){}

	public function getRadii() : Vector3{ return $this->radii; }

	public function getSegmentsPerAxis() : int{ return $this->segmentsPerAxis; }

	public static function read(ByteBufferReader $in) : self{
		$radii = CommonTypes::getVector3($in);
		$segmentsPerAxis = Byte::readUnsigned($in);

		return new self($radii, $segmentsPerAxis);
	}

	public function write(ByteBufferWriter $out) : void{
		CommonTypes::putVector3($out, $this->radii);
		Byte::writeUnsigned($out, $this->segmentsPerAxis);
	}
}
