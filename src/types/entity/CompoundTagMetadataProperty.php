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

use pocketmine\network\mcpe\protocol\PacketDecodeException;
use pocketmine\network\mcpe\protocol\serializer\PacketSerializer;
use pocketmine\network\mcpe\protocol\types\CacheableNbt;
use pocketmine\network\mcpe\protocol\types\GetTypeIdFromConstTrait;

final class CompoundTagMetadataProperty implements MetadataProperty{
	use GetTypeIdFromConstTrait;

	public const ID = EntityMetadataTypes::COMPOUND_TAG;

	/** @phpstan-var CacheableNbt<\pocketmine\nbt\tag\CompoundTag> */
	private CacheableNbt $value;

	/**
	 * @phpstan-param CacheableNbt<\pocketmine\nbt\tag\CompoundTag> $value
	 */
	public function __construct(CacheableNbt $value){
		$this->value = clone $value;
	}

	/**
	 * @phpstan-return CacheableNbt<\pocketmine\nbt\tag\CompoundTag>
	 */
	public function getValue() : CacheableNbt{
		return clone $this->value;
	}

	public function equals(MetadataProperty $other) : bool{
		return $other instanceof self and $other->value->getRoot()->equals($this->value->getRoot());
	}

	/**
	 * @throws PacketDecodeException
	 */
	public static function read(PacketSerializer $in) : self{
		return new self(new CacheableNbt($in->getNbtCompoundRoot()));
	}

	public function write(PacketSerializer $out) : void{
		$out->put($this->value->getEncodedNbt());
	}
}
