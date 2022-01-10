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

use pocketmine\math\Vector3;
use pocketmine\network\mcpe\protocol\serializer\PacketSerializer;
use pocketmine\network\mcpe\protocol\types\GetTypeIdFromConstTrait;

class Vec3MetadataProperty implements MetadataProperty{
	use GetTypeIdFromConstTrait;

	public const ID = EntityMetadataTypes::VECTOR3F;

	private Vector3 $value;

	public function __construct(Vector3 $value){
		$this->value = $value->asVector3();
	}

	public function getValue() : Vector3{
		return clone $this->value;
	}

	public static function read(PacketSerializer $in) : self{
		return new self($in->getVector3());
	}

	public function write(PacketSerializer $out) : void{
		$out->putVector3($this->value);
	}

	public function equals(MetadataProperty $other) : bool{
		return $other instanceof self and $other->value->equals($this->value);
	}
}
