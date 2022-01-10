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

final class IntMetadataProperty implements MetadataProperty{
	use GetTypeIdFromConstTrait;
	use IntegerishMetadataProperty;

	public const ID = EntityMetadataTypes::INT;

	protected function min() : int{
		return -0x80000000;
	}

	protected function max() : int{
		return 0x7fffffff;
	}

	public static function read(PacketSerializer $in) : self{
		return new self($in->getVarInt());
	}

	public function write(PacketSerializer $out) : void{
		$out->putVarInt($this->value);
	}
}
