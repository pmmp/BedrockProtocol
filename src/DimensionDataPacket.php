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
use pocketmine\network\mcpe\protocol\types\DimensionData;
use pocketmine\network\mcpe\protocol\types\DimensionNameIds;
use function count;

/**
 * Sets properties of different dimensions of the world, such as its Y axis bounds and generator used
 */
class DimensionDataPacket extends DataPacket implements ClientboundPacket{
	public const NETWORK_ID = ProtocolInfo::DIMENSION_DATA_PACKET;

	/**
	 * @var DimensionData[]
	 * @phpstan-var array<DimensionNameIds::*, DimensionData>
	 */
	private array $definitions;

	/**
	 * @generate-create-func
	 * @param DimensionData[] $definitions
	 * @phpstan-param array<DimensionNameIds::*, DimensionData> $definitions
	 */
	public static function create(array $definitions) : self{
		$result = new self;
		$result->definitions = $definitions;
		return $result;
	}

	/**
	 * @return DimensionData[]
	 * @phpstan-return array<DimensionNameIds::*, DimensionData>
	 */
	public function getDefinitions() : array{ return $this->definitions; }

	protected function decodePayload(PacketSerializer $in) : void{
		$this->definitions = [];

		for($i = 0, $count = $in->getUnsignedVarInt(); $i < $count; $i++){
			$dimensionNameId = $in->getString();
			$dimensionData = DimensionData::read($in);

			if(isset($this->definitions[$dimensionNameId])){
				throw new PacketDecodeException("Repeated dimension data for key \"$dimensionNameId\"");
			}
			if($dimensionNameId !== DimensionNameIds::OVERWORLD && $dimensionNameId !== DimensionNameIds::NETHER && $dimensionNameId !== DimensionNameIds::THE_END){
				throw new PacketDecodeException("Invalid dimension name ID \"$dimensionNameId\"");
			}
			$this->definitions[$dimensionNameId] = $dimensionData;
		}
	}

	protected function encodePayload(PacketSerializer $out) : void{
		$out->putUnsignedVarInt(count($this->definitions));

		foreach($this->definitions as $dimensionNameId => $definition){
			$out->putString((string) $dimensionNameId); //@phpstan-ignore-line
			$definition->write($out);
		}
	}

	public function handle(PacketHandlerInterface $handler) : bool{
		return $handler->handleDimensionData($this);
	}
}
