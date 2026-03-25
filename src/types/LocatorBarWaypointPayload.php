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

use pmmp\encoding\Byte;
use pmmp\encoding\ByteBufferReader;
use pmmp\encoding\ByteBufferWriter;
use pocketmine\network\mcpe\protocol\serializer\CommonTypes;
use Ramsey\Uuid\UuidInterface;

/**
 * @see LocatorBarPacket
 */
final class LocatorBarWaypointPayload{
	public function __construct(
		private UuidInterface $group,
		private LocatorBarWaypoint $waypoint,
		private int $action,
	){}

	public function getGroup() : UuidInterface{ return $this->group; }

	public function getWaypoint() : LocatorBarWaypoint{ return $this->waypoint; }

	public function getAction() : int{ return $this->action; }

	public static function read(ByteBufferReader $in) : self{
		$group = CommonTypes::getUUID($in);
		$waypoint = LocatorBarWaypoint::read($in);
		$action = Byte::readUnsigned($in);

		return new self(
			$group,
			$waypoint,
			$action
		);
	}

	public function write(ByteBufferWriter $out) : void{
		CommonTypes::putUUID($out, $this->group);
		$this->waypoint->write($out);
		Byte::writeUnsigned($out, $this->action);
	}
}
