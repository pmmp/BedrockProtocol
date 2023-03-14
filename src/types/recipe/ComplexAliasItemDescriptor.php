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

namespace pocketmine\network\mcpe\protocol\types\recipe;

use pocketmine\network\mcpe\protocol\serializer\PacketSerializer;
use pocketmine\network\mcpe\protocol\types\GetTypeIdFromConstTrait;

final class ComplexAliasItemDescriptor implements ItemDescriptor{
	use GetTypeIdFromConstTrait;

	public const ID = ItemDescriptorType::COMPLEX_ALIAS;

	public function __construct(
		private string $alias
	){}

	public function getAlias() : string{ return $this->alias; }

	public static function read(PacketSerializer $in) : self{
		$alias = $in->getString();

		return new self($alias);
	}

	public function write(PacketSerializer $out) : void{
		$out->putString($this->alias);
	}
}
