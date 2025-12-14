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
use pmmp\encoding\LE;
use function count;

final class AbilitiesData{
	/**
	 * @param AbilitiesLayer[] $abilityLayers
	 * @phpstan-param array<int, AbilitiesLayer> $abilityLayers
	 */
	public function __construct(
		private int $commandPermission,
		private int $playerPermission,
		private int $targetActorUniqueId, //This is a little-endian long, NOT a var-long. (WTF Mojang)
		private array $abilityLayers
	){}

	public function getCommandPermission() : int{ return $this->commandPermission; }

	public function getPlayerPermission() : int{ return $this->playerPermission; }

	public function getTargetActorUniqueId() : int{ return $this->targetActorUniqueId; }

	/**
	 * @return AbilitiesLayer[]
	 * @phpstan-return array<int, AbilitiesLayer>
	 */
	public function getAbilityLayers() : array{ return $this->abilityLayers; }

	public static function decode(ByteBufferReader $in) : self{
		$targetActorUniqueId = LE::readSignedLong($in); //WHY IS THIS NON-STANDARD?
		$playerPermission = Byte::readUnsigned($in);
		$commandPermission = Byte::readUnsigned($in);

		$abilityLayers = [];
		for($i = 0, $len = Byte::readUnsigned($in); $i < $len; $i++){
			$abilityLayers[] = AbilitiesLayer::decode($in);
		}

		return new self($commandPermission, $playerPermission, $targetActorUniqueId, $abilityLayers);
	}

	public function encode(ByteBufferWriter $out) : void{
		LE::writeSignedLong($out, $this->targetActorUniqueId);
		Byte::writeUnsigned($out, $this->playerPermission);
		Byte::writeUnsigned($out, $this->commandPermission);

		Byte::writeUnsigned($out, count($this->abilityLayers));
		foreach($this->abilityLayers as $abilityLayer){
			$abilityLayer->encode($out);
		}
	}
}
