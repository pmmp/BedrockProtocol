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

namespace pocketmine\network\mcpe\protocol\types\inventory\stackrequest;

use pmmp\encoding\Byte;
use pmmp\encoding\ByteBufferReader;
use pmmp\encoding\ByteBufferWriter;
use pocketmine\network\mcpe\protocol\serializer\CommonTypes;
use pocketmine\network\mcpe\protocol\types\GetTypeIdFromConstTrait;

/**
 * Drops some (or all) items from the source slot into the world as an item entity.
 */
final class DropStackRequestAction extends ItemStackRequestAction{
	use GetTypeIdFromConstTrait;

	public const ID = ItemStackRequestActionType::DROP;

	public function __construct(
		private int $count,
		private ItemStackRequestSlotInfo $source,
		private bool $randomly
	){}

	public function getCount() : int{ return $this->count; }

	public function getSource() : ItemStackRequestSlotInfo{ return $this->source; }

	public function isRandomly() : bool{ return $this->randomly; }

	public static function read(ByteBufferReader $in) : self{
		$count = Byte::readUnsigned($in);
		$source = ItemStackRequestSlotInfo::read($in);
		$random = CommonTypes::getBool($in);
		return new self($count, $source, $random);
	}

	public function write(ByteBufferWriter $out) : void{
		Byte::writeUnsigned($out, $this->count);
		$this->source->write($out);
		CommonTypes::putBool($out, $this->randomly);
	}
}
