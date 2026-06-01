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

namespace pocketmine\network\mcpe\protocol\types;

use pmmp\encoding\ByteBufferReader;
use pmmp\encoding\ByteBufferWriter;
use pmmp\encoding\VarInt;
use function count;

final class MapDataStorePropertyValue extends DataStorePropertyValue{
	public const ID = DataStorePropertyType::MAP;

	/**
	 * @param DataStoreMapEntry[] $entries
	 * @phpstan-param list<DataStoreMapEntry> $entries
	 */
	public function __construct(
		private readonly array $entries
	){}

	/**
	 * @return DataStoreMapEntry[]
	 * @phpstan-return list<DataStoreMapEntry>
	 */
	public function getEntries() : array{ return $this->entries; }

	public function getTypeId() : int{
		return self::ID;
	}

	public function write(ByteBufferWriter $out) : void{
		VarInt::writeUnsignedInt($out, count($this->entries));
		foreach($this->entries as $entry){
			$entry->write($out);
		}
	}

	public static function readPayload(ByteBufferReader $in) : self{
		$entries = [];
		for($i = 0, $len = VarInt::readUnsignedInt($in); $i < $len; ++$i){
			$entries[] = DataStoreMapEntry::read($in);
		}
		return new self($entries);
	}
}
