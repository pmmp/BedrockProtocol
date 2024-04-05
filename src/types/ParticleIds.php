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

final class ParticleIds{

	private function __construct(){
		//NOOP
	}
	public const BUBBLE = 1;
	public const BUBBLE_MANUAL = 2;
	public const CRITICAL = 3;
	public const BLOCK_FORCE_FIELD = 4;
	public const SMOKE = 5;
	public const EXPLODE = 6;
	public const EVAPORATION = 7;
	public const FLAME = 8;
	public const CANDLE_FLAME = 9;
	public const LAVA = 10;
	public const LARGE_SMOKE = 11;
	public const REDSTONE = 12;
	public const RISING_RED_DUST = 13;
	public const ITEM_BREAK = 14;
	public const SNOWBALL_POOF = 15;
	public const HUGE_EXPLODE = 16;
	public const HUGE_EXPLODE_SEED = 17;
	public const BREEZE_WIND_EXPLOSION = 18;
	public const MOB_FLAME = 19;
	public const HEART = 20;
	public const TERRAIN = 21;
	public const SUSPENDED_TOWN = 22, TOWN_AURA = 22;
	public const PORTAL = 23;
	//24 same as 23
	public const SPLASH = 25, WATER_SPLASH = 25;
	public const WATER_SPLASH_MANUAL = 26;
	public const WATER_WAKE = 27;
	public const DRIP_WATER = 28;
	public const DRIP_LAVA = 29;
	public const DRIP_HONEY = 30;
	public const STALACTITE_DRIP_WATER = 31;
	public const STALACTITE_DRIP_LAVA = 32;
	public const FALLING_DUST = 33, DUST = 33;
	public const MOB_SPELL = 34;
	public const MOB_SPELL_AMBIENT = 35;
	public const MOB_SPELL_INSTANTANEOUS = 36;
	public const INK = 37;
	public const SLIME = 38;
	public const RAIN_SPLASH = 39;
	public const VILLAGER_ANGRY = 40;
	public const VILLAGER_HAPPY = 41;
	public const ENCHANTMENT_TABLE = 42;
	public const TRACKING_EMITTER = 43;
	public const NOTE = 44;
	public const WITCH_SPELL = 45;
	public const CARROT = 46;
	public const MOB_APPEARANCE = 47;
	public const END_ROD = 48;
	public const DRAGONS_BREATH = 49;
	public const SPIT = 50;
	public const TOTEM = 51;
	public const FOOD = 52;
	public const FIREWORKS_STARTER = 53;
	public const FIREWORKS_SPARK = 54;
	public const FIREWORKS_OVERLAY = 55;
	public const BALLOON_GAS = 56;
	public const COLORED_FLAME = 57;
	public const SPARKLER = 58;
	public const CONDUIT = 59;
	public const BUBBLE_COLUMN_UP = 60;
	public const BUBBLE_COLUMN_DOWN = 61;
	public const SNEEZE = 62;
	public const SHULKER_BULLET = 63;
	public const BLEACH = 64;
	public const DRAGON_DESTROY_BLOCK = 65;
	public const MYCELIUM_DUST = 66;
	public const FALLING_RED_DUST = 67;
	public const CAMPFIRE_SMOKE = 68;
	public const TALL_CAMPFIRE_SMOKE = 69;
	public const DRAGON_BREATH_FIRE = 70;
	public const DRAGON_BREATH_TRAIL = 71;
	public const BLUE_FLAME = 72;
	public const SOUL = 73;
	public const OBSIDIAN_TEAR = 74;
	public const PORTAL_REVERSE = 75;
	public const SNOWFLAKE = 76;
	public const VIBRATION_SIGNAL = 77;
	public const SCULK_SENSOR_REDSTONE = 78;
	public const SPORE_BLOSSOM_SHOWER = 79;
	public const SPORE_BLOSSOM_AMBIENT = 80;
	public const WAX = 81;
	public const ELECTRIC_SPARK = 82;
	public const SHRIEK = 83;
	public const SCULK_SOUL = 84;
	public const SONIC_EXPLOSION = 85;
	public const BRUSH_DUST = 86;
	public const CHERRY_LEAVES = 87;
	public const DUST_PLUME = 88;
	public const WHITE_SMOKE = 89;
	public const VAULT_CONNECTION = 90;
	public const WIND_EXPLOSION = 91;
	public const WOLF_ARMOR_CRACK = 92;
}
