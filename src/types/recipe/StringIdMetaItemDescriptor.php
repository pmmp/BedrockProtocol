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

use pmmp\encoding\ByteBufferReader;
use pmmp\encoding\ByteBufferWriter;
use pmmp\encoding\LE;
use pocketmine\network\mcpe\protocol\serializer\CommonTypes;
use pocketmine\network\mcpe\protocol\types\GetTypeIdFromConstTrait;

final class StringIdMetaItemDescriptor implements ItemDescriptor{
	use GetTypeIdFromConstTrait;

	public const ID = ItemDescriptorType::STRING_ID_META;

	public function __construct(
		private string $id,
		private int $meta
	){
		if($meta < 0){
			throw new \InvalidArgumentException("Meta cannot be negative");
		}
	}

	public function getId() : string{ return $this->id; }

	public function getMeta() : int{ return $this->meta; }

	public static function read(ByteBufferReader $in) : self{
		$stringId = CommonTypes::getString($in);
		$meta = LE::readUnsignedShort($in);

		return new self($stringId, $meta);
	}

	public function write(ByteBufferWriter $out) : void{
		CommonTypes::putString($out, $this->id);
		LE::writeUnsignedShort($out, $this->meta);
	}
}
