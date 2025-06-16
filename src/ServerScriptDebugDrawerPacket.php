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

use pocketmine\network\mcpe\protocol\serializer\PacketSerializer;
use pocketmine\network\mcpe\protocol\types\PacketShapeData;
use function count;

class ServerScriptDebugDrawerPacket extends DataPacket implements ClientboundPacket{
	public const NETWORK_ID = ProtocolInfo::SERVER_SCRIPT_DEBUG_DRAWER_PACKET;

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

	protected function decodePayload(PacketSerializer $in) : void{
		$this->shapes = [];
		for($i = 0, $len = $in->getUnsignedVarInt(); $i < $len; ++$i){
			$this->shapes[] = PacketShapeData::read($in);
		}
	}

	protected function encodePayload(PacketSerializer $out) : void{
		$out->putUnsignedVarInt(count($this->shapes));
		foreach($this->shapes as $shape){
			$shape->write($out);
		}
	}

	public function handle(PacketHandlerInterface $handler) : bool{
		return $handler->handleServerScriptDebugDrawer($this);
	}
}
