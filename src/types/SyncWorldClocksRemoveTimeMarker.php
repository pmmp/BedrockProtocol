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
final class SyncWorldClocksRemoveTimeMarker extends SyncWorldClocksPayload{
	public const ID = SyncWorldClocksType::REMOVE_TIME_MARKER;

	/**
	 * @param int[] $markerIds
	 * @phpstan-param list<int> $markerIds
	 */
	public function __construct(
		private int $clockId,
		private array $markerIds,
	){}

	public function getTypeId() : int{
		return self::ID;
	}

	public function getClockId() : int{ return $this->clockId; }

	/**
	 * @return int[]
	 * @phpstan-return list<int>
	 */
	public function getMarkerIds() : array{ return $this->markerIds; }

	public static function read(ByteBufferReader $in) : self{
		$clockId = VarInt::readUnsignedLong($in);

		$markerIds = [];
		for($i = 0, $len = VarInt::readUnsignedInt($in); $i < $len; ++$i){
			$markerIds[] = VarInt::readUnsignedLong($in);
		}

		return new self(
			$clockId,
			$markerIds,
		);
	}

	public function write(ByteBufferWriter $out) : void{
		VarInt::writeUnsignedLong($out, $this->clockId);

		VarInt::writeUnsignedInt($out, count($this->markerIds));
		foreach($this->markerIds as $markerId){
			VarInt::writeUnsignedLong($out, $markerId);
		}
	}
}
