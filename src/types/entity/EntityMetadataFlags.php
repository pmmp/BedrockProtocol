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

final class EntityMetadataFlags{

	private function __construct(){
		//NOOP
	}
	public const ONFIRE = 0;
	public const SNEAKING = 1;
	public const RIDING = 2;
	public const SPRINTING = 3;
	public const ACTION = 4;
	public const INVISIBLE = 5;
	public const TEMPTED = 6;
	public const INLOVE = 7;
	public const SADDLED = 8;
	public const POWERED = 9;
	public const IGNITED = 10;
	public const BABY = 11;
	public const CONVERTING = 12;
	public const CRITICAL = 13;
	public const CAN_SHOW_NAMETAG = 14;
	public const ALWAYS_SHOW_NAMETAG = 15;
	public const IMMOBILE = 16, NO_AI = 16;
	public const SILENT = 17;
	public const WALLCLIMBING = 18;
	public const CAN_CLIMB = 19;
	public const SWIMMER = 20;
	public const CAN_FLY = 21;
	public const WALKER = 22;
	public const RESTING = 23;
	public const SITTING = 24;
	public const ANGRY = 25;
	public const INTERESTED = 26;
	public const CHARGED = 27;
	public const TAMED = 28;
	public const ORPHANED = 29;
	public const LEASHED = 30;
	public const SHEARED = 31;
	public const GLIDING = 32;
	public const ELDER = 33;
	public const MOVING = 34;
	public const BREATHING = 35;
	public const CHESTED = 36;
	public const STACKABLE = 37;
	public const SHOWBASE = 38;
	public const REARING = 39;
	public const VIBRATING = 40;
	public const IDLING = 41;
	public const EVOKER_SPELL = 42;
	public const CHARGE_ATTACK = 43;
	public const WASD_CONTROLLED = 44;
	public const CAN_POWER_JUMP = 45;
	public const CAN_DASH = 46;
	public const LINGER = 47;
	public const HAS_COLLISION = 48;
	public const AFFECTED_BY_GRAVITY = 49;
	public const FIRE_IMMUNE = 50;
	public const DANCING = 51;
	public const ENCHANTED = 52;
	public const SHOW_TRIDENT_ROPE = 53; // tridents show an animated rope when enchanted with loyalty after they are thrown and return to their owner. To be combined with DATA_OWNER_EID
	public const CONTAINER_PRIVATE = 54; //inventory is private, doesn't drop contents when killed if true
	public const TRANSFORMING = 55;
	public const SPIN_ATTACK = 56;
	public const SWIMMING = 57;
	public const BRIBED = 58; //dolphins have this set when they go to find treasure for the player
	public const PREGNANT = 59;
	public const LAYING_EGG = 60;
	public const RIDER_CAN_PICK = 61; //???
	public const TRANSITION_SITTING = 62;
	public const EATING = 63;
	public const LAYING_DOWN = 64;
	public const SNEEZING = 65;
	public const TRUSTING = 66;
	public const ROLLING = 67;
	public const SCARED = 68;
	public const IN_SCAFFOLDING = 69;
	public const OVER_SCAFFOLDING = 70;
	public const FALL_THROUGH_SCAFFOLDING = 71;
	public const BLOCKING = 72; //shield
	public const TRANSITION_BLOCKING = 73;
	public const BLOCKED_USING_SHIELD = 74;
	public const BLOCKED_USING_DAMAGED_SHIELD = 75;
	public const SLEEPING = 76;
	public const WANTS_TO_WAKE = 77;
	public const TRADE_INTEREST = 78;
	public const DOOR_BREAKER = 79; //...
	public const BREAKING_OBSTRUCTION = 80;
	public const DOOR_OPENER = 81; //...
	public const ILLAGER_CAPTAIN = 82;
	public const STUNNED = 83;
	public const ROARING = 84;
	public const DELAYED_ATTACKING = 85;
	public const AVOIDING_MOBS = 86;
	public const AVOIDING_BLOCK = 87;
	public const FACING_TARGET_TO_RANGE_ATTACK = 88;
	public const HIDDEN_WHEN_INVISIBLE = 89; //??????????????????
	public const IS_IN_UI = 90;
	public const STALKING = 91;
	public const EMOTING = 92;
	public const CELEBRATING = 93;
	public const ADMIRING = 94;
	public const CELEBRATING_SPECIAL = 95;
	public const OUT_OF_CONTROL = 96;
	public const RAM_ATTACK = 97;
	public const PLAYING_DEAD = 98;
	public const IN_ASCENDABLE_BLOCK = 99;
	public const OVER_DESCENDABLE_BLOCK = 100;
	public const CROAKING = 101;
	public const EAT_MOB = 102;
	public const JUMP_GOAL_JUMP = 103;
	public const EMERGING = 104;
	public const SNIFFING = 105;
	public const DIGGING = 106;
	public const SONIC_BOOM = 107;
	public const HAS_DASH_COOLDOWN = 108;
	public const PUSH_TOWARDS_CLOSEST_SPACE = 109;
	public const SCENTING = 110;
	public const RISING = 111;
	public const HAPPY = 112;
	public const SEARCHING = 113;
	public const CRAWLING = 114;
	public const TIMER_FLAG_1 = 115;
	public const TIMER_FLAG_2 = 116;
	public const TIMER_FLAG_3 = 117;
	public const BODY_ROTATION_BLOCKED = 118;
}
