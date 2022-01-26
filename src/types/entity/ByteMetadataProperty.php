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

namespace pocketmine\network\mcpe\protocol\types\entity;

use pocketmine\network\mcpe\protocol\serializer\PacketSerializer;
use pocketmine\network\mcpe\protocol\types\GetTypeIdFromConstTrait;
use pocketmine\utils\Binary;

final class ByteMetadataProperty implements MetadataProperty{
	use GetTypeIdFromConstTrait;
	use IntegerishMetadataProperty;

	public const ID = EntityMetadataTypes::BYTE;

	protected function min() : int{
		return -0x80;
	}

	protected function max() : int{
		return 0x7f;
	}

	public static function read(PacketSerializer $in) : self{
		return new self(Binary::signByte($in->getByte()));
	}

	public function write(PacketSerializer $out) : void{
		$out->putByte($this->value);
	}
}
