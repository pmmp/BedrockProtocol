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
	public const RECIPE_BOOK = 21;
	public const ENCHANTING_INPUT = 22;
	public const ENCHANTING_MATERIAL = 23;
	public const FURNACE_FUEL = 24;
	public const FURNACE_INGREDIENT = 25;
	public const FURNACE_RESULT = 26;
	public const HORSE_EQUIP = 27;
	public const HOTBAR = 28;
	public const INVENTORY = 29;
	public const SHULKER_BOX = 30;
	public const TRADE_INGREDIENT1 = 31;
	public const TRADE_INGREDIENT2 = 32;
	public const TRADE_RESULT_PREVIEW = 33;
	public const OFFHAND = 34;
	public const COMPOUND_CREATOR_INPUT = 35;
	public const COMPOUND_CREATOR_OUTPUT_PREVIEW = 36;
	public const ELEMENT_CONSTRUCTOR_OUTPUT_PREVIEW = 37;
	public const MATERIAL_REDUCER_INPUT = 38;
	public const MATERIAL_REDUCER_OUTPUT = 39;
	public const LAB_TABLE_INPUT = 40;
	public const LOOM_INPUT = 41;
	public const LOOM_DYE = 42;
	public const LOOM_MATERIAL = 43;
	public const LOOM_RESULT_PREVIEW = 44;
	public const BLAST_FURNACE_INGREDIENT = 45;
	public const SMOKER_INGREDIENT = 46;
	public const TRADE2_INGREDIENT1 = 47;
	public const TRADE2_INGREDIENT2 = 48;
	public const TRADE2_RESULT_PREVIEW = 49;
	public const GRINDSTONE_INPUT = 50;
	public const GRINDSTONE_ADDITIONAL = 51;
	public const GRINDSTONE_RESULT_PREVIEW = 52;
	public const STONECUTTER_INPUT = 53;
	public const STONECUTTER_RESULT_PREVIEW = 54;
	public const CARTOGRAPHY_INPUT = 55;
	public const CARTOGRAPHY_ADDITIONAL = 56;
	public const CARTOGRAPHY_RESULT_PREVIEW = 57;
	public const BARREL = 58;
	public const CURSOR = 59;
	public const CREATED_OUTPUT = 60;
	public const SMITHING_TABLE_TEMPLATE = 61;
	public const CRAFTER = 62;
}
