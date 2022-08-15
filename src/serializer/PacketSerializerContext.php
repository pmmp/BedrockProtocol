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

namespace pocketmine\network\mcpe\protocol\serializer;

/**
 * Contains information for a packet serializer specific to a given game session needed for packet encoding and decoding,
 * such as a dictionary of item runtime IDs to their internal string IDs.
 */
final class PacketSerializerContext{
	public function __construct(
		private ItemTypeDictionary $itemDictionary
	){}

	public function getItemDictionary() : ItemTypeDictionary{ return $this->itemDictionary; }
}
