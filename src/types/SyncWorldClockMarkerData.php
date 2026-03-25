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
use pmmp\encoding\LE;
use pmmp\encoding\VarInt;
use pocketmine\network\mcpe\protocol\serializer\CommonTypes;

/**
 * @see SyncWorldClockData&SyncWorldClocksAddTimeMarker
 */
final class SyncWorldClockMarkerData{
	public function __construct(
		private int $id,
		private string $name,
		private int $time,
		private ?int $period
	){}

	public function getId() : int{ return $this->id; }

	public function getName() : string{ return $this->name; }

	public function getTime() : int{ return $this->time; }

	public function getPeriod() : ?int{ return $this->period; }

	public static function read(ByteBufferReader $in) : self{
		$id = VarInt::readUnsignedLong($in);
		$name = CommonTypes::getString($in);
		$time = VarInt::readSignedInt($in);
		$period = CommonTypes::readOptional($in, LE::readSignedInt(...));

		return new self(
			$id,
			$name,
			$time,
			$period
		);
	}

	public function write(ByteBufferWriter $out) : void{
		VarInt::writeUnsignedLong($out, $this->id);
		CommonTypes::putString($out, $this->name);
		VarInt::writeSignedInt($out, $this->time);
		CommonTypes::writeOptional($out, $this->period, LE::writeSignedInt(...));
	}
}
