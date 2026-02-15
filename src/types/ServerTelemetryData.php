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
use pocketmine\network\mcpe\protocol\serializer\CommonTypes;

final class ServerTelemetryData{

	public function __construct(
		private string $serverId,
		private string $scenarioId,
		private string $worldId,
		private string $ownerId,
	){}

	public function getServerId() : string{ return $this->serverId; }

	public function getScenarioId() : string{ return $this->scenarioId; }

	public function getWorldId() : string{ return $this->worldId; }

	public function getOwnerId() : string{ return $this->ownerId; }

	public static function read(ByteBufferReader $in) : self{
		$serverId = CommonTypes::getString($in);
		$scenarioId = CommonTypes::getString($in);
		$worldId = CommonTypes::getString($in);
		$ownerId = CommonTypes::getString($in);

		return new self(
			$serverId,
			$scenarioId,
			$worldId,
			$ownerId
		);
	}

	public function write(ByteBufferWriter $out) : void{
		CommonTypes::putString($out, $this->serverId);
		CommonTypes::putString($out, $this->scenarioId);
		CommonTypes::putString($out, $this->worldId);
		CommonTypes::putString($out, $this->ownerId);
	}
}
