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

/**
 * @see MemoryCategoryCounter
 */
final class MemoryCategory{
	public const UNKNOWN = 0;
	public const INVALID_SIZE_UNKNOWN = 1;
	public const ACTOR = 2;
	public const ACTOR_ANIMATION = 3;
	public const ACTOR_RENDERING = 4;
	public const BALANCER = 5;
	public const BLOCK_TICKING_QUEUES = 6;
	public const BIOME_STORAGE = 7;
	public const CEREAL = 8;
	public const CIRCUIT_SYSTEM = 9;
	public const CLIENT = 10;
	public const COMMANDS = 11;
	public const DB_STORAGE = 12;
	public const DEBUG = 13;
	public const DOCUMENTATION = 14;
	public const ECS_SYSTEMS = 15;
	public const FMOD = 16;
	public const FONTS = 17;
	public const IM_GUI = 18;
	public const INPUT = 19;
	public const JSON_UI = 20;
	public const JSON_UI_CONTROL_FACTORY_JSON = 21;
	public const JSON_UI_CONTROL_TREE = 22;
	public const JSON_UI_CONTROL_TREE_CONTROL_ELEMENT = 23;
	public const JSON_UI_CONTROL_TREE_POPULATE_DATA_BINDING = 24;
	public const JSON_UI_CONTROL_TREE_POPULATE_FOCUS = 25;
	public const JSON_UI_CONTROL_TREE_POPULATE_LAYOUT = 26;
	public const JSON_UI_CONTROL_TREE_POPULATE_OTHER = 27;
	public const JSON_UI_CONTROL_TREE_POPULATE_SPRITE = 28;
	public const JSON_UI_CONTROL_TREE_POPULATE_TEXT = 29;
	public const JSON_UI_CONTROL_TREE_POPULATE_TTS = 30;
	public const JSON_UI_CONTROL_TREE_VISIBILITY = 31;
	public const JSON_UI_CREATE_UI = 32;
	public const JSON_UI_DEFS = 33;
	public const JSON_UI_LAYOUT_MANAGER = 34;
	public const JSON_UI_LAYOUT_MANAGER_REMOVE_DEPENDENCIES = 35;
	public const JSON_UI_LAYOUT_MANAGER_INIT_VARIABLE = 36;
	public const LANGUAGES = 37;
	public const LEVEL = 38;
	public const LEVEL_STRUCTURES = 39;
	public const LEVEL_CHUNK = 40;
	public const LEVEL_CHUNK_GEN = 41;
	public const LEVEL_CHUNK_GEN_THREAD_LOCAL = 42;
	public const NETWORK = 43;
	public const MARKETPLACE = 44;
	public const MATERIAL_DRAGON_COMPILED_DEFINITION = 45;
	public const MATERIAL_DRAGON_MATERIAL = 46;
	public const MATERIAL_DRAGON_RESOURCE = 47;
	public const MATERIAL_DRAGON_UNIFORM_MAP = 48;
	public const MATERIAL_RENDER_MATERIAL = 49;
	public const MATERIAL_RENDER_MATERIAL_GROUP = 50;
	public const MATERIAL_VARIATION_MANAGER = 51;
	public const MOLANG = 52;
	public const ORE_UI = 53;
	public const PERSONA = 54;
	public const PLAYER = 55;
	public const RENDER_CHUNK = 56;
	public const RENDER_CHUNK_INDEX_BUFFER = 57;
	public const RENDER_CHUNK_VERTEX_BUFFER = 58;
	public const RENDERING = 59;
	public const RENDERING_LIBRARY = 60;
	public const REQUEST_LOG = 61;
	public const RESOURCE_PACKS = 62;
	public const SOUND = 63;
	public const SUB_CHUNK_BIOME_DATA = 64;
	public const SUB_CHUNK_BLOCK_DATA = 65;
	public const SUB_CHUNK_LIGHT_DATA = 66;
	public const TEXTURES = 67;
	public const VR = 68;
	public const WEATHER_RENDERER = 69;
	public const WORLD_GENERATOR = 70;
	public const TASKS = 71;
	public const TEST = 72;
	public const SCRIPTING = 73;
	public const SCRIPTING_RUNTIME = 74;
	public const SCRIPTING_CONTEXT = 75;
	public const SCRIPTING_CONTEXT_BINDINGS_MC = 76;
	public const SCRIPTING_CONTEXT_BINDINGS_GT = 77;
	public const SCRIPTING_CONTEXT_RUN = 78;
	public const DATA_DRIVEN_UI = 79;
	public const DATA_DRIVEN_UI_DEFS = 80;
}
