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

/**
 * Version numbers and packet IDs for the current Minecraft PE protocol
 */
final class ProtocolInfo{

	private function __construct(){
		//NOOP
	}

	/**
	 * NOTE TO DEVELOPERS
	 * Do not waste your time or ours submitting pull requests changing game and/or protocol version numbers.
	 * Pull requests changing game and/or protocol version numbers will be closed.
	 *
	 * This file is generated automatically, do not edit it manually.
	 */

	/** Actual Minecraft: PE protocol version */
	public const CURRENT_PROTOCOL = 712;
	/** Current Minecraft PE version reported by the server. This is usually the earliest currently supported version. */
	public const MINECRAFT_VERSION = 'v1.21.20';
	/** Version number sent to clients in ping responses. */
	public const MINECRAFT_VERSION_NETWORK = '1.21.20';

	public const LOGIN_PACKET = 0x01;
	public const PLAY_STATUS_PACKET = 0x02;
	public const SERVER_TO_CLIENT_HANDSHAKE_PACKET = 0x03;
	public const CLIENT_TO_SERVER_HANDSHAKE_PACKET = 0x04;
	public const DISCONNECT_PACKET = 0x05;
	public const RESOURCE_PACKS_INFO_PACKET = 0x06;
	public const RESOURCE_PACK_STACK_PACKET = 0x07;
	public const RESOURCE_PACK_CLIENT_RESPONSE_PACKET = 0x08;
	public const TEXT_PACKET = 0x09;
	public const SET_TIME_PACKET = 0x0a;
	public const START_GAME_PACKET = 0x0b;
	public const ADD_PLAYER_PACKET = 0x0c;
	public const ADD_ACTOR_PACKET = 0x0d;
	public const REMOVE_ACTOR_PACKET = 0x0e;
	public const ADD_ITEM_ACTOR_PACKET = 0x0f;
	public const SERVER_PLAYER_POST_MOVE_POSITION_PACKET = 0x10;
	public const TAKE_ITEM_ACTOR_PACKET = 0x11;
	public const MOVE_ACTOR_ABSOLUTE_PACKET = 0x12;
	public const MOVE_PLAYER_PACKET = 0x13;
	public const PASSENGER_JUMP_PACKET = 0x14;
	public const UPDATE_BLOCK_PACKET = 0x15;
	public const ADD_PAINTING_PACKET = 0x16;

	public const LEVEL_SOUND_EVENT_PACKET_V1 = 0x18;
	public const LEVEL_EVENT_PACKET = 0x19;
	public const BLOCK_EVENT_PACKET = 0x1a;
	public const ACTOR_EVENT_PACKET = 0x1b;
	public const MOB_EFFECT_PACKET = 0x1c;
	public const UPDATE_ATTRIBUTES_PACKET = 0x1d;
	public const INVENTORY_TRANSACTION_PACKET = 0x1e;
	public const MOB_EQUIPMENT_PACKET = 0x1f;
	public const MOB_ARMOR_EQUIPMENT_PACKET = 0x20;
	public const INTERACT_PACKET = 0x21;
	public const BLOCK_PICK_REQUEST_PACKET = 0x22;
	public const ACTOR_PICK_REQUEST_PACKET = 0x23;
	public const PLAYER_ACTION_PACKET = 0x24;

	public const HURT_ARMOR_PACKET = 0x26;
	public const SET_ACTOR_DATA_PACKET = 0x27;
	public const SET_ACTOR_MOTION_PACKET = 0x28;
	public const SET_ACTOR_LINK_PACKET = 0x29;
	public const SET_HEALTH_PACKET = 0x2a;
	public const SET_SPAWN_POSITION_PACKET = 0x2b;
	public const ANIMATE_PACKET = 0x2c;
	public const RESPAWN_PACKET = 0x2d;
	public const CONTAINER_OPEN_PACKET = 0x2e;
	public const CONTAINER_CLOSE_PACKET = 0x2f;
	public const PLAYER_HOTBAR_PACKET = 0x30;
	public const INVENTORY_CONTENT_PACKET = 0x31;
	public const INVENTORY_SLOT_PACKET = 0x32;
	public const CONTAINER_SET_DATA_PACKET = 0x33;
	public const CRAFTING_DATA_PACKET = 0x34;

	public const GUI_DATA_PICK_ITEM_PACKET = 0x36;

	public const BLOCK_ACTOR_DATA_PACKET = 0x38;
	public const PLAYER_INPUT_PACKET = 0x39;
	public const LEVEL_CHUNK_PACKET = 0x3a;
	public const SET_COMMANDS_ENABLED_PACKET = 0x3b;
	public const SET_DIFFICULTY_PACKET = 0x3c;
	public const CHANGE_DIMENSION_PACKET = 0x3d;
	public const SET_PLAYER_GAME_TYPE_PACKET = 0x3e;
	public const PLAYER_LIST_PACKET = 0x3f;
	public const SIMPLE_EVENT_PACKET = 0x40;
	public const LEGACY_TELEMETRY_EVENT_PACKET = 0x41;
	public const SPAWN_EXPERIENCE_ORB_PACKET = 0x42;
	public const CLIENTBOUND_MAP_ITEM_DATA_PACKET = 0x43;
	public const MAP_INFO_REQUEST_PACKET = 0x44;
	public const REQUEST_CHUNK_RADIUS_PACKET = 0x45;
	public const CHUNK_RADIUS_UPDATED_PACKET = 0x46;

	public const GAME_RULES_CHANGED_PACKET = 0x48;
	public const CAMERA_PACKET = 0x49;
	public const BOSS_EVENT_PACKET = 0x4a;
	public const SHOW_CREDITS_PACKET = 0x4b;
	public const AVAILABLE_COMMANDS_PACKET = 0x4c;
	public const COMMAND_REQUEST_PACKET = 0x4d;
	public const COMMAND_BLOCK_UPDATE_PACKET = 0x4e;
	public const COMMAND_OUTPUT_PACKET = 0x4f;
	public const UPDATE_TRADE_PACKET = 0x50;
	public const UPDATE_EQUIP_PACKET = 0x51;
	public const RESOURCE_PACK_DATA_INFO_PACKET = 0x52;
	public const RESOURCE_PACK_CHUNK_DATA_PACKET = 0x53;
	public const RESOURCE_PACK_CHUNK_REQUEST_PACKET = 0x54;
	public const TRANSFER_PACKET = 0x55;
	public const PLAY_SOUND_PACKET = 0x56;
	public const STOP_SOUND_PACKET = 0x57;
	public const SET_TITLE_PACKET = 0x58;
	public const ADD_BEHAVIOR_TREE_PACKET = 0x59;
	public const STRUCTURE_BLOCK_UPDATE_PACKET = 0x5a;
	public const SHOW_STORE_OFFER_PACKET = 0x5b;
	public const PURCHASE_RECEIPT_PACKET = 0x5c;
	public const PLAYER_SKIN_PACKET = 0x5d;
	public const SUB_CLIENT_LOGIN_PACKET = 0x5e;
	public const AUTOMATION_CLIENT_CONNECT_PACKET = 0x5f;
	public const SET_LAST_HURT_BY_PACKET = 0x60;
	public const BOOK_EDIT_PACKET = 0x61;
	public const NPC_REQUEST_PACKET = 0x62;
	public const PHOTO_TRANSFER_PACKET = 0x63;
	public const MODAL_FORM_REQUEST_PACKET = 0x64;
	public const MODAL_FORM_RESPONSE_PACKET = 0x65;
	public const SERVER_SETTINGS_REQUEST_PACKET = 0x66;
	public const SERVER_SETTINGS_RESPONSE_PACKET = 0x67;
	public const SHOW_PROFILE_PACKET = 0x68;
	public const SET_DEFAULT_GAME_TYPE_PACKET = 0x69;
	public const REMOVE_OBJECTIVE_PACKET = 0x6a;
	public const SET_DISPLAY_OBJECTIVE_PACKET = 0x6b;
	public const SET_SCORE_PACKET = 0x6c;
	public const LAB_TABLE_PACKET = 0x6d;
	public const UPDATE_BLOCK_SYNCED_PACKET = 0x6e;
	public const MOVE_ACTOR_DELTA_PACKET = 0x6f;
	public const SET_SCOREBOARD_IDENTITY_PACKET = 0x70;
	public const SET_LOCAL_PLAYER_AS_INITIALIZED_PACKET = 0x71;
	public const UPDATE_SOFT_ENUM_PACKET = 0x72;
	public const NETWORK_STACK_LATENCY_PACKET = 0x73;

	public const SPAWN_PARTICLE_EFFECT_PACKET = 0x76;
	public const AVAILABLE_ACTOR_IDENTIFIERS_PACKET = 0x77;
	public const LEVEL_SOUND_EVENT_PACKET_V2 = 0x78;
	public const NETWORK_CHUNK_PUBLISHER_UPDATE_PACKET = 0x79;
	public const BIOME_DEFINITION_LIST_PACKET = 0x7a;
	public const LEVEL_SOUND_EVENT_PACKET = 0x7b;
	public const LEVEL_EVENT_GENERIC_PACKET = 0x7c;
	public const LECTERN_UPDATE_PACKET = 0x7d;

	public const CLIENT_CACHE_STATUS_PACKET = 0x81;
	public const ON_SCREEN_TEXTURE_ANIMATION_PACKET = 0x82;
	public const MAP_CREATE_LOCKED_COPY_PACKET = 0x83;
	public const STRUCTURE_TEMPLATE_DATA_REQUEST_PACKET = 0x84;
	public const STRUCTURE_TEMPLATE_DATA_RESPONSE_PACKET = 0x85;

	public const CLIENT_CACHE_BLOB_STATUS_PACKET = 0x87;
	public const CLIENT_CACHE_MISS_RESPONSE_PACKET = 0x88;
	public const EDUCATION_SETTINGS_PACKET = 0x89;
	public const EMOTE_PACKET = 0x8a;
	public const MULTIPLAYER_SETTINGS_PACKET = 0x8b;
	public const SETTINGS_COMMAND_PACKET = 0x8c;
	public const ANVIL_DAMAGE_PACKET = 0x8d;
	public const COMPLETED_USING_ITEM_PACKET = 0x8e;
	public const NETWORK_SETTINGS_PACKET = 0x8f;
	public const PLAYER_AUTH_INPUT_PACKET = 0x90;
	public const CREATIVE_CONTENT_PACKET = 0x91;
	public const PLAYER_ENCHANT_OPTIONS_PACKET = 0x92;
	public const ITEM_STACK_REQUEST_PACKET = 0x93;
	public const ITEM_STACK_RESPONSE_PACKET = 0x94;
	public const PLAYER_ARMOR_DAMAGE_PACKET = 0x95;
	public const CODE_BUILDER_PACKET = 0x96;
	public const UPDATE_PLAYER_GAME_TYPE_PACKET = 0x97;
	public const EMOTE_LIST_PACKET = 0x98;
	public const POSITION_TRACKING_D_B_SERVER_BROADCAST_PACKET = 0x99;
	public const POSITION_TRACKING_D_B_CLIENT_REQUEST_PACKET = 0x9a;
	public const DEBUG_INFO_PACKET = 0x9b;
	public const PACKET_VIOLATION_WARNING_PACKET = 0x9c;
	public const MOTION_PREDICTION_HINTS_PACKET = 0x9d;
	public const ANIMATE_ENTITY_PACKET = 0x9e;
	public const CAMERA_SHAKE_PACKET = 0x9f;
	public const PLAYER_FOG_PACKET = 0xa0;
	public const CORRECT_PLAYER_MOVE_PREDICTION_PACKET = 0xa1;
	public const ITEM_COMPONENT_PACKET = 0xa2;

	public const CLIENTBOUND_DEBUG_RENDERER_PACKET = 0xa4;
	public const SYNC_ACTOR_PROPERTY_PACKET = 0xa5;
	public const ADD_VOLUME_ENTITY_PACKET = 0xa6;
	public const REMOVE_VOLUME_ENTITY_PACKET = 0xa7;
	public const SIMULATION_TYPE_PACKET = 0xa8;
	public const NPC_DIALOGUE_PACKET = 0xa9;
	public const EDU_URI_RESOURCE_PACKET = 0xaa;
	public const CREATE_PHOTO_PACKET = 0xab;
	public const UPDATE_SUB_CHUNK_BLOCKS_PACKET = 0xac;

	public const SUB_CHUNK_PACKET = 0xae;
	public const SUB_CHUNK_REQUEST_PACKET = 0xaf;
	public const PLAYER_START_ITEM_COOLDOWN_PACKET = 0xb0;
	public const SCRIPT_MESSAGE_PACKET = 0xb1;
	public const CODE_BUILDER_SOURCE_PACKET = 0xb2;
	public const TICKING_AREAS_LOAD_STATUS_PACKET = 0xb3;
	public const DIMENSION_DATA_PACKET = 0xb4;
	public const AGENT_ACTION_EVENT_PACKET = 0xb5;
	public const CHANGE_MOB_PROPERTY_PACKET = 0xb6;
	public const LESSON_PROGRESS_PACKET = 0xb7;
	public const REQUEST_ABILITY_PACKET = 0xb8;
	public const REQUEST_PERMISSIONS_PACKET = 0xb9;
	public const TOAST_REQUEST_PACKET = 0xba;
	public const UPDATE_ABILITIES_PACKET = 0xbb;
	public const UPDATE_ADVENTURE_SETTINGS_PACKET = 0xbc;
	public const DEATH_INFO_PACKET = 0xbd;
	public const EDITOR_NETWORK_PACKET = 0xbe;
	public const FEATURE_REGISTRY_PACKET = 0xbf;
	public const SERVER_STATS_PACKET = 0xc0;
	public const REQUEST_NETWORK_SETTINGS_PACKET = 0xc1;
	public const GAME_TEST_REQUEST_PACKET = 0xc2;
	public const GAME_TEST_RESULTS_PACKET = 0xc3;
	public const UPDATE_CLIENT_INPUT_LOCKS_PACKET = 0xc4;

	public const CAMERA_PRESETS_PACKET = 0xc6;
	public const UNLOCKED_RECIPES_PACKET = 0xc7;

	public const CAMERA_INSTRUCTION_PACKET = 0x12c;
	public const COMPRESSED_BIOME_DEFINITION_LIST_PACKET = 0x12d;
	public const TRIM_DATA_PACKET = 0x12e;
	public const OPEN_SIGN_PACKET = 0x12f;
	public const AGENT_ANIMATION_PACKET = 0x130;
	public const REFRESH_ENTITLEMENTS_PACKET = 0x131;
	public const PLAYER_TOGGLE_CRAFTER_SLOT_REQUEST_PACKET = 0x132;
	public const SET_PLAYER_INVENTORY_OPTIONS_PACKET = 0x133;
	public const SET_HUD_PACKET = 0x134;
	public const AWARD_ACHIEVEMENT_PACKET = 0x135;
	public const CLIENTBOUND_CLOSE_FORM_PACKET = 0x136;

	public const SERVERBOUND_LOADING_SCREEN_PACKET = 0x138;
	public const JIGSAW_STRUCTURE_DATA_PACKET = 0x139;
	public const CURRENT_STRUCTURE_FEATURE_PACKET = 0x13a;
	public const SERVERBOUND_DIAGNOSTICS_PACKET = 0x13b;
}
