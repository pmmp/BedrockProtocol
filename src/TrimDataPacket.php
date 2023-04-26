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
use pocketmine\network\mcpe\protocol\types\TrimMaterial;
use pocketmine\network\mcpe\protocol\types\TrimPattern;
use function count;

class TrimDataPacket extends DataPacket implements ClientboundPacket{
	public const NETWORK_ID = ProtocolInfo::TRIM_DATA_PACKET;

	/**
	 * @var TrimPattern[]
	 * @phpstan-var list<TrimPattern>
	 */
	private array $trimPatterns;
	/**
	 * @var TrimMaterial[]
	 * @phpstan-var list<TrimMaterial>
	 */
	private array $trimMaterials;

	/**
	 * @generate-create-func
	 * @param TrimPattern[]  $trimPatterns
	 * @param TrimMaterial[] $trimMaterials
	 * @phpstan-param list<TrimPattern>  $trimPatterns
	 * @phpstan-param list<TrimMaterial> $trimMaterials
	 */
	public static function create(array $trimPatterns, array $trimMaterials) : self{
		$result = new self;
		$result->trimPatterns = $trimPatterns;
		$result->trimMaterials = $trimMaterials;
		return $result;
	}

	/**
	 * @return TrimPattern[]
	 * @phpstan-return list<TrimPattern>
	 */
	public function getTrimPatterns() : array{ return $this->trimPatterns; }

	/**
	 * @return TrimMaterial[]
	 * @phpstan-return list<TrimMaterial>
	 */
	public function getTrimMaterials() : array{ return $this->trimMaterials; }

	protected function decodePayload(PacketSerializer $in) : void{
		$this->trimPatterns = [];
		for($i = 0, $count = $in->getUnsignedVarInt(); $i < $count; ++$i){
			$this->trimPatterns[] = TrimPattern::read($in);
		}
		$this->trimMaterials = [];
		for($i = 0, $count = $in->getUnsignedVarInt(); $i < $count; ++$i){
			$this->trimMaterials[] = TrimMaterial::read($in);
		}
	}

	protected function encodePayload(PacketSerializer $out) : void{
		$out->putUnsignedVarInt(count($this->trimPatterns));
		foreach($this->trimPatterns as $trimPattern){
			$trimPattern->write($out);
		}
		$out->putUnsignedVarInt(count($this->trimMaterials));
		foreach($this->trimMaterials as $trimMaterial){
			$trimMaterial->write($out);
		}
	}

	public function handle(PacketHandlerInterface $handler) : bool{
		return $handler->handleTrimData($this);
	}
}
