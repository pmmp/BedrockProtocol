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

namespace pocketmine\network\mcpe\protocol\types\recipe;

use pocketmine\network\mcpe\protocol\serializer\PacketSerializer;
use pocketmine\network\mcpe\protocol\types\inventory\ItemStack;

final class SmithingTransformRecipe extends RecipeWithTypeId{

	public function __construct(
		int $typeId,
		private string $recipeId,
		private RecipeIngredient $input,
		private RecipeIngredient $addition,
		private ItemStack $output,
		private string $blockName,
		private int $recipeNetId
	){
		parent::__construct($typeId);
	}

	public function getRecipeId() : string{ return $this->recipeId; }

	public function getInput() : RecipeIngredient{ return $this->input; }

	public function getAddition() : RecipeIngredient{ return $this->addition; }

	public function getOutput() : ItemStack{ return $this->output; }

	public function getBlockName() : string{ return $this->blockName; }

	public function getRecipeNetId() : int{ return $this->recipeNetId; }

	public static function decode(int $typeId, PacketSerializer $in) : self{
		$recipeId = $in->getString();
		$input = $in->getRecipeIngredient();
		$addition = $in->getRecipeIngredient();
		$output = $in->getItemStackWithoutStackId();
		$blockName = $in->getString();
		$recipeNetId = $in->readGenericTypeNetworkId();

		return new self(
			$typeId,
			$recipeId,
			$input,
			$addition,
			$output,
			$blockName,
			$recipeNetId
		);
	}

	public function encode(PacketSerializer $out) : void{
		$out->putString($this->recipeId);
		$out->putRecipeIngredient($this->input);
		$out->putRecipeIngredient($this->addition);
		$out->putItemStackWithoutStackId($this->output);
		$out->putString($this->blockName);
		$out->writeGenericTypeNetworkId($this->recipeNetId);
	}
}