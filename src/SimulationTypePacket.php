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

class SimulationTypePacket extends DataPacket implements ClientboundPacket{
	public const NETWORK_ID = ProtocolInfo::SIMULATION_TYPE_PACKET;

	public const GAME = 0;
	public const EDITOR = 1;
	public const TEST = 2;

	private int $type;

	/**
	 * @generate-create-func
	 */
	public static function create(int $type) : self{
		$result = new self;
		$result->type = $type;
		return $result;
	}

	public function getType() : int{ return $this->type; }

	protected function decodePayload(PacketSerializer $in) : void{
		$this->type = $in->getByte();
	}

	protected function encodePayload(PacketSerializer $out) : void{
		$out->putByte($this->type);
	}

	public function handle(PacketHandlerInterface $handler) : bool{
		return $handler->handleSimulationType($this);
	}
}
