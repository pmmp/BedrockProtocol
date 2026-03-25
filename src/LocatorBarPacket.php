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

namespace pocketmine\network\mcpe\protocol;

use pmmp\encoding\ByteBufferReader;
use pmmp\encoding\ByteBufferWriter;
use pmmp\encoding\VarInt;
use pocketmine\network\mcpe\protocol\types\LocatorBarWaypointPayload;
use function count;

class LocatorBarPacket extends DataPacket implements ClientboundPacket{
	public const NETWORK_ID = ProtocolInfo::LOCATOR_BAR_PACKET;

	/**
	 * @var LocatorBarWaypointPayload[]
	 * @phpstan-var list<LocatorBarWaypointPayload>
	 */
	private array $waypoints;

	/**
	 * @generate-create-func
	 * @param LocatorBarWaypointPayload[] $waypoints
	 * @phpstan-param list<LocatorBarWaypointPayload> $waypoints
	 */
	public static function create(array $waypoints) : self{
		$result = new self;
		$result->waypoints = $waypoints;
		return $result;
	}

	protected function decodePayload(ByteBufferReader $in) : void{
		$this->waypoints = [];
		for($i = 0, $len = VarInt::readUnsignedInt($in); $i < $len; ++$i){
			$this->waypoints[] = LocatorBarWaypointPayload::read($in);
		}
	}

	protected function encodePayload(ByteBufferWriter $out) : void{
		VarInt::writeUnsignedInt($out, count($this->waypoints));
		foreach($this->waypoints as $waypoint){
			$waypoint->write($out);
		}
	}

	public function handle(PacketHandlerInterface $handler) : bool{
		return $handler->handleLocatorBar($this);
	}
}
