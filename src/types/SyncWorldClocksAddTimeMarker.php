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

/**
 * @see SyncWorldClocksPacket
 */
final class SyncWorldClocksAddTimeMarker extends SyncWorldClocksPayload{
	public const ID = SyncWorldClocksType::ADD_TIME_MARKER;

	/**
	 * @param SyncWorldClockMarkerData[] $markers
	 * @phpstan-param list<SyncWorldClockMarkerData> $markers
	 */
	public function __construct(
		private int $clockId,
		private array $markers,
	){}

	public function getTypeId() : int{
		return self::ID;
	}

	public function getClockId() : int{ return $this->clockId; }

	/**
	 * @return SyncWorldClockMarkerData[]
	 * @phpstan-return list<SyncWorldClockMarkerData>
	 */
	public function getMarkers() : array{ return $this->markers; }

	public static function read(ByteBufferReader $in) : self{
		$clockId = VarInt::readUnsignedLong($in);

		$markers = [];
		for($i = 0, $len = VarInt::readUnsignedInt($in); $i < $len; ++$i){
			$markers[] = SyncWorldClockMarkerData::read($in);
		}

		return new self(
			$clockId,
			$markers,
		);
	}

	public function write(ByteBufferWriter $out) : void{
		VarInt::writeUnsignedLong($out, $this->clockId);

		VarInt::writeUnsignedInt($out, count($this->markers));
		foreach($this->markers as $marker){
			$marker->write($out);
		}
	}
}
