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

trait DisappearStackRequestActionTrait{
	final public function __construct(
		private int $count,
		private ItemStackRequestSlotInfo $source
	){}

	final public function getCount() : int{ return $this->count; }

	final public function getSource() : ItemStackRequestSlotInfo{ return $this->source; }

	public static function read(PacketSerializer $in) : self{
		$count = $in->getByte();
		$source = ItemStackRequestSlotInfo::read($in);
		return new self($count, $source);
	}

	public function write(PacketSerializer $out) : void{
		$out->putByte($this->count);
		$this->source->write($out);
	}
}
