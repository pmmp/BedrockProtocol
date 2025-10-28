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
use pocketmine\network\mcpe\protocol\types\PacketShapeData;
use function count;

class DebugDrawerPacket extends DataPacket implements ClientboundPacket{
	public const NETWORK_ID = ProtocolInfo::DEBUG_DRAWER_PACKET;

	/**
	 * @var PacketShapeData[]
	 * @phpstan-var list<PacketShapeData>
	 */
	private array $shapes;

	/**
	 * @generate-create-func
	 * @param PacketShapeData[] $shapes
	 * @phpstan-param list<PacketShapeData> $shapes
	 */
	public static function create(array $shapes) : self{
		$result = new self;
		$result->shapes = $shapes;
		return $result;
	}

	/**
	 * @return PacketShapeData[]
	 * @phpstan-return list<PacketShapeData>
	 */
	public function getShapes() : array{ return $this->shapes; }

	protected function decodePayload(ByteBufferReader $in) : void{
		$this->shapes = [];
		for($i = 0, $len = VarInt::readUnsignedInt($in); $i < $len; ++$i){
			$this->shapes[] = PacketShapeData::read($in);
		}
	}

	protected function encodePayload(ByteBufferWriter $out) : void{
		VarInt::writeUnsignedInt($out, count($this->shapes));
		foreach($this->shapes as $shape){
			$shape->write($out);
		}
	}

	public function handle(PacketHandlerInterface $handler) : bool{
		return $handler->handleDebugDrawer($this);
	}
}
