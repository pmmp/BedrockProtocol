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

namespace pocketmine\network\mcpe\protocol\types\inventory;

final class ContainerUIIds{

	private function __construct(){
		//NOOP
	}

	public const ANVIL_INPUT = 0;
	public const ANVIL_MATERIAL = 1;
	public const ANVIL_RESULT_PREVIEW = 2;
	public const SMITHING_TABLE_INPUT = 3;
	public const SMITHING_TABLE_MATERIAL = 4;
	public const SMITHING_TABLE_RESULT_PREVIEW = 5;
	public const ARMOR = 6;
	public const LEVEL_ENTITY = 7;
	public const BEACON_PAYMENT = 8;
	public const BREWING_STAND_INPUT = 9;
	public const BREWING_STAND_RESULT = 10;
	public const BREWING_STAND_FUEL = 11;
	public const COMBINED_HOTBAR_AND_INVENTORY = 12;
	public const CRAFTING_INPUT = 13;
	public const CRAFTING_OUTPUT_PREVIEW = 14;
	public const RECIPE_CONSTRUCTION = 15;
	public const RECIPE_NATURE = 16;
	public const RECIPE_ITEMS = 17;
	public const RECIPE_SEARCH = 18;
	public const RECIPE_SEARCH_BAR = 19;
	public const RECIPE_EQUIPMENT = 20;
	public const ENCHANTING_INPUT = 21;
	public const ENCHANTING_MATERIAL = 22;
	public const FURNACE_FUEL = 23;
	public const FURNACE_INGREDIENT = 24;
	public const FURNACE_RESULT = 25;
	public const HORSE_EQUIP = 26;
	public const HOTBAR = 27;
	public const INVENTORY = 28;
	public const SHULKER_BOX = 29;
	public const TRADE_INGREDIENT1 = 30;
	public const TRADE_INGREDIENT2 = 31;
	public const TRADE_RESULT_PREVIEW = 32;
	public const OFFHAND = 33;
	public const COMPOUND_CREATOR_INPUT = 34;
	public const COMPOUND_CREATOR_OUTPUT_PREVIEW = 35;
	public const ELEMENT_CONSTRUCTOR_OUTPUT_PREVIEW = 36;
	public const MATERIAL_REDUCER_INPUT = 37;
	public const MATERIAL_REDUCER_OUTPUT = 38;
	public const LAB_TABLE_INPUT = 39;
	public const LOOM_INPUT = 40;
	public const LOOM_DYE = 41;
	public const LOOM_MATERIAL = 42;
	public const LOOM_RESULT_PREVIEW = 43;
	public const BLAST_FURNACE_INGREDIENT = 44;
	public const SMOKER_INGREDIENT = 45;
	public const TRADE2_INGREDIENT1 = 46;
	public const TRADE2_INGREDIENT2 = 47;
	public const TRADE2_RESULT_PREVIEW = 48;
	public const GRINDSTONE_INPUT = 49;
	public const GRINDSTONE_ADDITIONAL = 50;
	public const GRINDSTONE_RESULT_PREVIEW = 51;
	public const STONECUTTER_INPUT = 52;
	public const STONECUTTER_RESULT_PREVIEW = 53;
	public const CARTOGRAPHY_INPUT = 54;
	public const CARTOGRAPHY_ADDITIONAL = 55;
	public const CARTOGRAPHY_RESULT_PREVIEW = 56;
	public const BARREL = 57;
	public const CURSOR = 58;
	public const CREATED_OUTPUT = 59;
}
