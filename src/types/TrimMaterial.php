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
use pocketmine\network\mcpe\protocol\serializer\CommonTypes;

final class TrimMaterial{

	public function __construct(
		private string $materialId,
		private string $color,
		private string $itemId
	){}

	public function getMaterialId() : string{ return $this->materialId; }

	public function getColor() : string{ return $this->color; }

	public function getItemId() : string{ return $this->itemId; }

	public static function read(ByteBufferReader $in) : self{
		$materialId = CommonTypes::getString($in);
		$color = CommonTypes::getString($in);
		$itemId = CommonTypes::getString($in);
		return new self($materialId, $color, $itemId);
	}

	public function write(ByteBufferWriter $out) : void{
		CommonTypes::putString($out, $this->materialId);
		CommonTypes::putString($out, $this->color);
		CommonTypes::putString($out, $this->itemId);
	}
}
