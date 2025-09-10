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
use pocketmine\network\mcpe\protocol\PacketDecodeException;

final class AbilitiesLayer{

	public const LAYER_CACHE = 0;
	public const LAYER_BASE = 1;
	public const LAYER_SPECTATOR = 2;
	public const LAYER_COMMANDS = 3;
	public const LAYER_EDITOR = 4;
	public const LAYER_LOADING_SCREEN = 5;

	public const ABILITY_BUILD = 0;
	public const ABILITY_MINE = 1;
	public const ABILITY_DOORS_AND_SWITCHES = 2; //disabling this also disables dropping items (???)
	public const ABILITY_OPEN_CONTAINERS = 3;
	public const ABILITY_ATTACK_PLAYERS = 4;
	public const ABILITY_ATTACK_MOBS = 5;
	public const ABILITY_OPERATOR = 6;
	public const ABILITY_TELEPORT = 7;
	public const ABILITY_INVULNERABLE = 8;
	public const ABILITY_FLYING = 9;
	public const ABILITY_ALLOW_FLIGHT = 10;
	public const ABILITY_INFINITE_RESOURCES = 11; //in vanilla they call this "instabuild", which is a bad name
	public const ABILITY_LIGHTNING = 12; //???
	private const ABILITY_FLY_SPEED = 13;
	private const ABILITY_WALK_SPEED = 14;
	public const ABILITY_MUTED = 15;
	public const ABILITY_WORLD_BUILDER = 16;
	public const ABILITY_NO_CLIP = 17;
	public const ABILITY_PRIVILEGED_BUILDER = 18;
	public const ABILITY_VERTICAL_FLY_SPEED = 19;

	public const NUMBER_OF_ABILITIES = 20;

	/**
	 * @param bool[] $boolAbilities
	 * @phpstan-param array<self::ABILITY_*, bool> $boolAbilities
	 */
	public function __construct(
		private int $layerId,
		private array $boolAbilities,
		private ?float $flySpeed,
		private ?float $verticalFlySpeed,
		private ?float $walkSpeed
	){}

	public function getLayerId() : int{ return $this->layerId; }

	/**
	 * Returns a list of abilities set/overridden by this layer. If the ability value is not set, the index is omitted.
	 * @return bool[]
	 * @phpstan-return array<self::ABILITY_*, bool>
	 */
	public function getBoolAbilities() : array{ return $this->boolAbilities; }

	public function getFlySpeed() : ?float{ return $this->flySpeed; }

	public function getVerticalFlySpeed() : ?float{ return $this->verticalFlySpeed; }

	public function getWalkSpeed() : ?float{ return $this->walkSpeed; }

	public static function decode(ByteBufferReader $in) : self{
		$layerId = LE::readUnsignedShort($in);
		$setAbilities = /* TODO: check if this should be unsigned */ LE::readSignedInt($in);
		$setAbilityValues = /* TODO: check if this should be unsigned */ LE::readSignedInt($in);
		$flySpeed = LE::readFloat($in);
		$verticalFlySpeed = LE::readFloat($in);
		$walkSpeed = LE::readFloat($in);

		$boolAbilities = [];
		for($i = 0; $i < self::NUMBER_OF_ABILITIES; $i++){
			if($i === self::ABILITY_FLY_SPEED || $i === self::ABILITY_WALK_SPEED){
				continue;
			}
			if(($setAbilities & (1 << $i)) !== 0){
				$boolAbilities[$i] = ($setAbilityValues & (1 << $i)) !== 0;
			}
		}
		if(($setAbilities & (1 << self::ABILITY_FLY_SPEED)) === 0){
			if($flySpeed !== 0.0){
				throw new PacketDecodeException("Fly speed should be zero if the layer does not set it");
			}
			$flySpeed = null;
		}
		if(($setAbilities & (1 << self::ABILITY_VERTICAL_FLY_SPEED)) === 0){
			if($verticalFlySpeed !== 0.0){
				throw new PacketDecodeException("Vertical fly speed should be zero if the layer does not set it");
			}
			$verticalFlySpeed = null;
		}
		if(($setAbilities & (1 << self::ABILITY_WALK_SPEED)) === 0){
			if($walkSpeed !== 0.0){
				throw new PacketDecodeException("Walk speed should be zero if the layer does not set it");
			}
			$walkSpeed = null;
		}

		return new self($layerId, $boolAbilities, $flySpeed, $verticalFlySpeed, $walkSpeed);
	}

	public function encode(ByteBufferWriter $out) : void{
		/* TODO: check if this should be unsigned */ LE::writeUnsignedShort($out, $this->layerId);

		$setAbilities = 0;
		$setAbilityValues = 0;
		foreach($this->boolAbilities as $ability => $value){
			$setAbilities |= (1 << $ability);
			$setAbilityValues |= ($value ? 1 << $ability : 0);
		}
		if($this->flySpeed !== null){
			$setAbilities |= (1 << self::ABILITY_FLY_SPEED);
		}
		if($this->verticalFlySpeed !== null){
			$setAbilities |= (1 << self::ABILITY_VERTICAL_FLY_SPEED);
		}
		if($this->walkSpeed !== null){
			$setAbilities |= (1 << self::ABILITY_WALK_SPEED);
		}

		/* TODO: check if this should be unsigned */ LE::writeSignedInt($out, $setAbilities);
		/* TODO: check if this should be unsigned */ LE::writeSignedInt($out, $setAbilityValues);
		LE::writeFloat($out, $this->flySpeed ?? 0);
		LE::writeFloat($out, $this->verticalFlySpeed ?? 0);
		LE::writeFloat($out, $this->walkSpeed ?? 0);
	}
}
