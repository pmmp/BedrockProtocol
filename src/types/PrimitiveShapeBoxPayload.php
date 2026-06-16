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
use pocketmine\math\Vector3;
use pocketmine\network\mcpe\protocol\serializer\CommonTypes;

final class PrimitiveShapeBoxPayload extends PrimitiveShapePayload{
	use GetTypeIdFromConstTrait;

	public const ID = PrimitiveShapeType::PAYLOAD_TYPE_BOX;

	public function __construct(
		private Vector3 $boxBound,
	){}

	public function getBoxBound() : Vector3{ return $this->boxBound; }

	public static function read(ByteBufferReader $in) : self{
		return new self(CommonTypes::getVector3($in));
	}

	public function write(ByteBufferWriter $out) : void{
		CommonTypes::putVector3($out, $this->boxBound);
	}
}
