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

use pocketmine\network\mcpe\protocol\serializer\PacketSerializer;

trait TakeOrPlaceStackRequestActionTrait{
	final public function __construct(
		private int $count,
		private ItemStackRequestSlotInfo $source,
		private ItemStackRequestSlotInfo $destination
	){}

	final public function getCount() : int{ return $this->count; }

	final public function getSource() : ItemStackRequestSlotInfo{ return $this->source; }

	final public function getDestination() : ItemStackRequestSlotInfo{ return $this->destination; }

	public static function read(PacketSerializer $in) : self{
		$count = $in->getByte();
		$src = ItemStackRequestSlotInfo::read($in);
		$dst = ItemStackRequestSlotInfo::read($in);
		return new self($count, $src, $dst);
	}

	public function write(PacketSerializer $out) : void{
		$out->putByte($this->count);
		$this->source->write($out);
		$this->destination->write($out);
	}
}
