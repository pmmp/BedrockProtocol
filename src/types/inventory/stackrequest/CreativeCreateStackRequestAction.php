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
use pocketmine\network\mcpe\protocol\types\GetTypeIdFromConstTrait;

/**
 * Creates an item by copying it from the creative inventory. This is treated as a crafting action by vanilla.
 */
final class CreativeCreateStackRequestAction extends ItemStackRequestAction{
	use GetTypeIdFromConstTrait;

	public const ID = ItemStackRequestActionType::CREATIVE_CREATE;

	public function __construct(
		private int $creativeItemId
	){}

	public function getCreativeItemId() : int{ return $this->creativeItemId; }

	public static function read(PacketSerializer $in) : self{
		$creativeItemId = $in->readCreativeItemNetId();
		return new self($creativeItemId);
	}

	public function write(PacketSerializer $out) : void{
		$out->writeCreativeItemNetId($this->creativeItemId);
	}
}
