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
use pocketmine\network\mcpe\protocol\types\BlockPosition;
use pocketmine\network\mcpe\protocol\types\GetTypeIdFromConstTrait;

final class BlockPosMetadataProperty implements MetadataProperty{
	use GetTypeIdFromConstTrait;

	public const ID = EntityMetadataTypes::POS;

	public function __construct(
		private BlockPosition $value
	){}

	public function getValue() : BlockPosition{
		return $this->value;
	}

	public static function read(PacketSerializer $in) : self{
		return new self($in->getSignedBlockPosition());
	}

	public function write(PacketSerializer $out) : void{
		$out->putSignedBlockPosition($this->value);
	}

	public function equals(MetadataProperty $other) : bool{
		return $other instanceof self and $other->value->equals($this->value);
	}
}
