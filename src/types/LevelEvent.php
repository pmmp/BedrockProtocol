<?php

/*
 *
 *  ____            _        _   __  __ _                  __  __ ____
 * |  _ \ ___   ___| | _____| |_|  \/  (_)_ __   ___      |  \/  |  _ \
 * | |_) / _ \ / __| |/ / _ \ __| |\/| | | '_ \ / _ \_____| |\/| | |_) |
 * |  __/ (_) | (__|   <  __/ |_| |  | | | | | |  __/_____| |  | |  __/
 * |_|   \___/ \___|_|\_\___|\__|_|  |_|_|_| |_|\___|     |_|  |_|_|
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * @author PocketMine Team
 * @link http://www.pocketmine.net/
 *
 *
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

	public const SOUND_GHAST = 1007;
	public const SOUND_GHAST_SHOOT = 1008;
	public const SOUND_BLAZE_SHOOT = 1009;
	public const SOUND_DOOR_BUMP = 1010;

	public const SOUND_DOOR_CRASH = 1012;

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

	//TODO: check 2000-2017
	public const PARTICLE_SHOOT = 2000;
	public const PARTICLE_DESTROY = 2001;
	public const PARTICLE_SPLASH = 2002;
	public const PARTICLE_EYE_DESPAWN = 2003;
	public const PARTICLE_SPAWN = 2004;

	public const GUARDIAN_CURSE = 2006;

	public const PARTICLE_BLOCK_FORCE_FIELD = 2008;
	public const PARTICLE_PROJECTILE_HIT = 2009;
	public const PARTICLE_DRAGON_EGG_TELEPORT = 2010;

	public const PARTICLE_ENDERMAN_TELEPORT = 2013;
	public const PARTICLE_PUNCH_BLOCK = 2014;

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
	public const CAULDRON_CLEAN_BANNER = 3509;

	public const BLOCK_START_BREAK = 3600;
	public const BLOCK_STOP_BREAK = 3601;

	public const SET_DATA = 4000;

	public const PLAYERS_SLEEPING = 9800;

	public const ADD_PARTICLE_MASK = 0x4000;
}
