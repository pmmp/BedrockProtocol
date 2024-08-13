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

namespace pocketmine\network\mcpe\protocol\types\entity;

final class EntityMetadataProperties{

	private function __construct(){
		//NOOP
	}

	/*
	 * Readers beware: this isn't a nice list. Some of the properties have different types for different entities, and
	 * are used for entirely different things.
	 */
	public const FLAGS = 0;
	public const HEALTH = 1; //int (minecart/boat)
	public const VARIANT = 2; //int
	public const COLOR = 3; //byte
	public const NAMETAG = 4; //string
	public const OWNER_EID = 5; //long
	public const TARGET_EID = 6; //long
	public const AIR = 7; //short
	public const POTION_COLOR = 8; //int (ARGB!)
	public const POTION_AMBIENT = 9; //byte
	public const JUMP_DURATION = 10; //byte
	public const HURT_TIME = 11; //int (minecart/boat)
	public const HURT_DIRECTION = 12; //int (minecart/boat)
	public const PADDLE_TIME_LEFT = 13; //float
	public const PADDLE_TIME_RIGHT = 14; //float
	public const EXPERIENCE_VALUE = 15; //int (xp orb)
	public const MINECART_DISPLAY_BLOCK = 16; //int (block runtime ID)
	public const HORSE_FLAGS = 16; //int
	public const FIREWORK_ITEM = 16; //compoundtag
	/* 16 (byte) used by wither skull */
	public const MINECART_DISPLAY_OFFSET = 17; //int
	public const SHOOTER_ID = 17; //long (used by arrows)
	public const MINECART_HAS_DISPLAY = 18; //byte (must be 1 for minecart to show block inside)
	public const HORSE_TYPE = 19; //byte
	public const CREEPER_SWELL = 19; //int
	public const CREEPER_SWELL_PREVIOUS = 20; //int
	public const CREEPER_SWELL_DIRECTION = 21; //byte
	public const CHARGE_AMOUNT = 22; //int8, used for ghasts and also crossbow charging
	public const ENDERMAN_HELD_ITEM_ID = 23; //short
	public const ENTITY_AGE = 24; //short
	/* 25 (int) used by horse, (byte) used by witch */
	public const PLAYER_FLAGS = 26; //byte
	public const PLAYER_INDEX = 27; //int, used for marker colours and agent nametag colours
	public const PLAYER_BED_POSITION = 28; //blockpos
	public const FIREBALL_POWER_X = 29; //float
	public const FIREBALL_POWER_Y = 30;
	public const FIREBALL_POWER_Z = 31;
	/* 32 (unknown) */
	public const FISH_X = 33; //float
	public const FISH_Z = 34; //float
	public const FISH_ANGLE = 35; //float
	public const POTION_AUX_VALUE = 36; //short
	public const LEAD_HOLDER_EID = 37; //long
	public const SCALE = 38; //float
	public const HAS_NPC_COMPONENT = 39; //byte (???)
	public const NPC_SKIN_INDEX = 40; //string
	public const NPC_ACTIONS = 41; //string (maybe JSON blob?)
	public const MAX_AIR = 42; //short
	public const MARK_VARIANT = 43; //int
	public const CONTAINER_TYPE = 44; //byte (ContainerComponent)
	public const CONTAINER_BASE_SIZE = 45; //int (ContainerComponent)
	public const CONTAINER_EXTRA_SLOTS_PER_STRENGTH = 46; //int (used for llamas, inventory size is baseSize + thisProp * strength)
	public const BLOCK_TARGET = 47; //block coords (ender crystal)
	public const WITHER_INVULNERABLE_TICKS = 48; //int
	public const WITHER_TARGET_1 = 49; //long
	public const WITHER_TARGET_2 = 50; //long
	public const WITHER_TARGET_3 = 51; //long
	public const WITHER_AERIAL_ATTACK = 52; //short
	public const BOUNDING_BOX_WIDTH = 53; //float
	public const BOUNDING_BOX_HEIGHT = 54; //float
	public const FUSE_LENGTH = 55; //int
	public const RIDER_SEAT_POSITION = 56; //vector3f
	public const RIDER_ROTATION_LOCKED = 57; //byte
	public const RIDER_MAX_ROTATION = 58; //float
	public const RIDER_MIN_ROTATION = 59; //float
	public const RIDER_SEAT_ROTATION_OFFSET = 60; //TODO: find type
	public const AREA_EFFECT_CLOUD_RADIUS = 61; //float
	public const AREA_EFFECT_CLOUD_WAITING = 62; //int
	public const AREA_EFFECT_CLOUD_PARTICLE_ID = 63; //int
	public const SHULKER_PEEK_ID = 64; //int
	public const SHULKER_ATTACH_FACE = 65; //byte
	public const SHULKER_ATTACHED = 66; //byte (TODO: check this - comment said it was a short)
	public const SHULKER_ATTACH_POS = 67; //block coords
	public const TRADING_PLAYER_EID = 68; //long
	public const CAREER = 69; //int
	public const HAS_COMMAND_BLOCK = 70; //byte
	public const COMMAND_BLOCK_COMMAND = 71; //string
	public const COMMAND_BLOCK_LAST_OUTPUT = 72; //string
	public const COMMAND_BLOCK_TRACK_OUTPUT = 73; //byte
	public const CONTROLLING_RIDER_SEAT_NUMBER = 74; //byte
	public const STRENGTH = 75; //int
	public const MAX_STRENGTH = 76; //int
	public const EVOKER_SPELL_CASTING_COLOR = 77; //int
	public const LIMITED_LIFE = 78;
	public const ARMOR_STAND_POSE_INDEX = 79; //int
	public const ENDER_CRYSTAL_TIME_OFFSET = 80; //int
	public const ALWAYS_SHOW_NAMETAG = 81; //byte: -1 = default, 0 = only when looked at, 1 = always
	public const COLOR_2 = 82; //byte
	public const NAME_AUTHOR = 83; //string
	public const SCORE_TAG = 84; //string
	public const BALLOON_ATTACHED_ENTITY = 85; //int64, entity unique ID of owner
	public const PUFFERFISH_SIZE = 86; //byte
	public const BOAT_BUBBLE_TIME = 87; //int (time in bubble column)
	public const PLAYER_AGENT_EID = 88; //long
	public const SITTING_AMOUNT = 89; //float
	public const SITTING_AMOUNT_PREVIOUS = 90; //float
	public const EAT_COUNTER = 91; //int (used by pandas)
	public const FLAGS2 = 92; //long (extended data flags)
	public const LAYING_AMOUNT = 93; //float (used by pandas)
	public const LAYING_AMOUNT_PREVIOUS = 94; //float (used by pandas)
	public const AREA_EFFECT_CLOUD_DURATION = 95; //int
	public const AREA_EFFECT_CLOUD_SPAWN_TIME = 96; //int
	public const AREA_EFFECT_CLOUD_RADIUS_PER_TICK = 97; //float, usually negative
	public const AREA_EFFECT_CLOUD_RADIUS_CHANGE_ON_PICKUP = 98; //float
	public const AREA_EFFECT_CLOUD_PICKUP_COUNT = 99; //int
	public const INTERACTIVE_TAG = 100; //string (button text)
	public const TRADE_TIER = 101; //int
	public const MAX_TRADE_TIER = 102; //int
	public const TRADE_XP = 103; //int
	public const SKIN_ID = 104; //int ???
	public const SPAWNING_FRAMES = 105; //int - related to wither
	public const COMMAND_BLOCK_TICK_DELAY = 106; //int
	public const COMMAND_BLOCK_EXECUTE_ON_FIRST_TICK = 107; //byte
	public const AMBIENT_SOUND_INTERVAL_MIN = 108; //float
	public const AMBIENT_SOUND_INTERVAL_RANGE = 109; //float
	public const AMBIENT_SOUND_EVENT = 110; //string
	public const FALL_DAMAGE_MULTIPLIER = 111; //float
	public const NAME_RAW_TEXT = 112; //string
	public const CAN_RIDE_TARGET = 113; //byte
	public const LOW_TIER_CURED_TRADE_DISCOUNT = 114; //int
	public const HIGH_TIER_CURED_TRADE_DISCOUNT = 115; //int
	public const NEARBY_CURED_TRADE_DISCOUNT = 116; //int
	public const NEARBY_CURED_DISCOUNT_TIME_STAMP = 117; //int
	public const HITBOX = 118; //compound
	public const IS_BUOYANT = 119; //byte
	public const FREEZING_EFFECT_STRENGTH = 120; //float
	public const BUOYANCY_DATA = 121; //string
	public const GOAT_HORN_COUNT = 122; //int
	public const BASE_RUNTIME_ID = 123; //string
	public const MOVEMENT_SOUND_DISTANCE_OFFSET = 124;
	public const HEARTBEAT_INTERVAL_TICKS = 125; //int
	public const HEARTBEAT_LEVEL_SOUND_EVENT = 126; //int
	public const PLAYER_DEATH_POSITION = 127; //blockpos
	public const PLAYER_DEATH_DIMENSION = 128; //int
	public const PLAYER_HAS_DIED = 129; //byte
	public const COLLISION_BOX = 130; //compound
}
