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

final class LevelEvent{
	private function __construct(){
		//NOOP
	}

	public const SOUND_CLICK = 1000;
	public const SOUND_CLICK_FAIL = 1001;
	public const SOUND_SHOOT = 1002;
	public const SOUND_DOOR = 1003;
	public const SOUND_FIZZ = 1004;
	public const SOUND_IGNITE = 1005;
	public const SOUND_PLAY_RECORDING = 1006;
	public const SOUND_GHAST = 1007;
	public const SOUND_GHAST_SHOOT = 1008;
	public const SOUND_BLAZE_SHOOT = 1009;
	public const SOUND_DOOR_BUMP = 1010;

	public const SOUND_DOOR_CRASH = 1012;

	public const SOUND_ZOMBIE_INFECTED = 1016;
	public const SOUND_ZOMBIE_CONVERT = 1017;
	public const SOUND_ENDERMAN_TELEPORT = 1018;

	public const SOUND_ANVIL_BREAK = 1020;
	public const SOUND_ANVIL_USE = 1021;
	public const SOUND_ANVIL_FALL = 1022;

	public const SOUND_POP = 1030;

	public const SOUND_PORTAL = 1032;

	public const SOUND_ITEMFRAME_ADD_ITEM = 1040;
	public const SOUND_ITEMFRAME_REMOVE = 1041;
	public const SOUND_ITEMFRAME_PLACE = 1042;
	public const SOUND_ITEMFRAME_REMOVE_ITEM = 1043;
	public const SOUND_ITEMFRAME_ROTATE_ITEM = 1044;

	public const SOUND_CAMERA = 1050;
	public const SOUND_ORB = 1051;
	public const SOUND_TOTEM = 1052;

	public const SOUND_ARMOR_STAND_BREAK = 1060;
	public const SOUND_ARMOR_STAND_HIT = 1061;
	public const SOUND_ARMOR_STAND_FALL = 1062;
	public const SOUND_ARMOR_STAND_PLACE = 1063;
	public const SOUND_POINTED_DRIPSTONE_FALL = 1064;
	public const SOUND_DYE_USED = 1065;
	public const SOUND_INK_SAC_USED = 1066;

	public const PARTICLE_SHOOT = 2000;
	public const PARTICLE_DESTROY = 2001; //sound + particles
	public const PARTICLE_SPLASH = 2002;
	public const PARTICLE_EYE_DESPAWN = 2003;
	public const PARTICLE_SPAWN = 2004;
	public const BONE_MEAL_USE = 2005; //sound + green particles
	public const GUARDIAN_CURSE = 2006;
	public const PARTICLE_DEATH_SMOKE = 2007;
	public const PARTICLE_BLOCK_FORCE_FIELD = 2008;
	public const PARTICLE_PROJECTILE_HIT = 2009;
	public const PARTICLE_DRAGON_EGG_TELEPORT = 2010;
	public const PARTICLE_CROP_EATEN = 2011;
	public const PARTICLE_CRITICAL_HIT = 2012;
	public const PARTICLE_ENDERMAN_TELEPORT = 2013;
	public const PARTICLE_PUNCH_BLOCK = 2014;
	public const PARTICLE_BUBBLE = 2015;
	public const PARTICLE_EVAPORATE = 2016;
	public const PARTICLE_ARMOR_STAND_DESTROY = 2017;
	public const PARTICLE_EGG_PUNCH = 2018;
	public const PARTICLE_EGG_BREAK = 2019;
	public const PARTICLE_ICE_EVAPORATE = 2020;
	public const PARTICLE_DESTROY_NO_SOUND = 2021;
	public const PARTICLE_KNOCKBACK_ROAR = 2022; //spews out tons of white particles
	public const PARTICLE_TELEPORT_TRAIL = 2023;
	public const PARTICLE_POINT_CLOUD = 2024;
	public const PARTICLE_EXPLODE = 2025; //data >= 2 = huge explode seed, otherwise huge explode
	public const PARTICLE_BLOCK_EXPLODE = 2026;
	public const PARTICLE_VIBRATION_SIGNAL = 2027;
	public const PARTICLE_DRIPSTONE_DRIP = 2028;
	public const PARTICLE_FIZZ = 2029;
	public const COPPER_WAX_ON = 2030; //sound + particles
	public const COPPER_WAX_OFF = 2031; //sound + particles
	public const COPPER_SCRAPE = 2032; //sound + particles
	public const PARTICLE_ELECTRIC_SPARK = 2033; //lightning rod
	public const PARTICLE_TURTLE_EGG_GROW = 2034;
	public const PARTICLE_SCULK_SHRIEK = 2035;
	public const PARTICLE_SCULK_CATALYST_BLOOM = 2036;

	public const PARTICLE_DUST_PLUME = 2040;

	public const START_RAIN = 3001;
	public const START_THUNDER = 3002;
	public const STOP_RAIN = 3003;
	public const STOP_THUNDER = 3004;
	public const PAUSE_GAME = 3005; //data: 1 to pause, 0 to resume
	public const PAUSE_GAME_NO_SCREEN = 3006; //data: 1 to pause, 0 to resume - same effect as normal pause but without screen
	public const SET_GAME_SPEED = 3007; //x coordinate of pos = scale factor (default 1.0)

	public const REDSTONE_TRIGGER = 3500;
	public const CAULDRON_EXPLODE = 3501;
	public const CAULDRON_DYE_ARMOR = 3502;
	public const CAULDRON_CLEAN_ARMOR = 3503;
	public const CAULDRON_FILL_POTION = 3504;
	public const CAULDRON_TAKE_POTION = 3505;
	public const CAULDRON_FILL_WATER = 3506;
	public const CAULDRON_TAKE_WATER = 3507;
	public const CAULDRON_ADD_DYE = 3508;
	public const CAULDRON_CLEAN_BANNER = 3509; //particle + sound
	public const PARTICLE_CAULDRON_FLUSH = 3510;
	public const PARTICLE_AGENT_SPAWN = 3511;
	public const SOUND_CAULDRON_FILL_LAVA = 3512;
	public const SOUND_CAULDRON_TAKE_LAVA = 3513;
	public const SOUND_CAULDRON_FILL_POWDER_SNOW = 3514;
	public const SOUND_CAULDRON_TAKE_POWDER_SNOW = 3515;

	public const BLOCK_START_BREAK = 3600;
	public const BLOCK_STOP_BREAK = 3601;
	public const BLOCK_BREAK_SPEED = 3602;
	public const PARTICLE_PUNCH_BLOCK_DOWN = 3603;
	public const PARTICLE_PUNCH_BLOCK_UP = 3604;
	public const PARTICLE_PUNCH_BLOCK_NORTH = 3605;
	public const PARTICLE_PUNCH_BLOCK_SOUTH = 3606;
	public const PARTICLE_PUNCH_BLOCK_WEST = 3607;
	public const PARTICLE_PUNCH_BLOCK_EAST = 3608;
	public const PARTICLE_SHOOT_WHITE_SMOKE = 3609;
	public const PARTICLE_BREEZE_WIND_EXPLOSION = 3610;
	public const PARTICLE_TRIAL_SPAWNER_DETECTION = 3611;
	public const PARTICLE_TRIAL_SPAWNER_SPAWNING = 3612;
	public const PARTICLE_TRIAL_SPAWNER_EJECTING = 3613;
	public const PARTICLE_WIND_EXPLOSION = 3614;

	public const SET_DATA = 4000;

	public const PLAYERS_SLEEPING = 9800;
	public const NUMBER_OF_SLEEPING_PLAYERS = 9801;

	public const JUMP_PREVENTED = 9810;
	public const ANIMATION_VAULT_ACTIVATE = 9811;
	public const ANIMATION_VAULT_DEACTIVATE = 9812;
	public const ANIMATION_VAULT_EJECT_ITEM = 9813;

	public const ADD_PARTICLE_MASK = 0x4000;
}
