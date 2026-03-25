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
final class SyncWorldClocksSyncState extends SyncWorldClocksPayload{
	public const ID = SyncWorldClocksType::SYNC_STATE;

	/**
	 * @param SyncWorldClockStateData[] $clockData
	 * @phpstan-param list<SyncWorldClockStateData> $clockData
	 */
	public function __construct(
		private array $clockData,
	){}

	public function getTypeId() : int{
		return self::ID;
	}

	/**
	 * @return SyncWorldClockStateData[]
	 * @phpstan-return list<SyncWorldClockStateData>
	 */
	public function getClockData() : array{ return $this->clockData; }

	public static function read(ByteBufferReader $in) : self{
		$clockData = [];
		for($i = 0, $len = VarInt::readUnsignedInt($in); $i < $len; ++$i){
			$clockData[] = SyncWorldClockStateData::read($in);
		}

		return new self(
			$clockData,
		);
	}

	public function write(ByteBufferWriter $out) : void{
		VarInt::writeUnsignedInt($out, count($this->clockData));
		foreach($this->clockData as $data){
			$data->write($out);
		}
	}
}
