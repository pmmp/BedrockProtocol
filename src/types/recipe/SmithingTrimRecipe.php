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

namespace pocketmine\network\mcpe\protocol\types\recipe;

use pocketmine\network\mcpe\protocol\serializer\PacketSerializer;

final class SmithingTrimRecipe extends RecipeWithTypeId{

	public function __construct(
		int $typeId,
		private string $recipeId,
		private RecipeIngredient $template,
		private RecipeIngredient $input,
		private RecipeIngredient $addition,
		private string $blockName,
		private int $recipeNetId
	){
		parent::__construct($typeId);
	}

	public function getRecipeId() : string{ return $this->recipeId; }

	public function getTemplate() : RecipeIngredient{ return $this->template; }

	public function getInput() : RecipeIngredient{ return $this->input; }

	public function getAddition() : RecipeIngredient{ return $this->addition; }

	public function getBlockName() : string{ return $this->blockName; }

	public function getRecipeNetId() : int{ return $this->recipeNetId; }

	public static function decode(int $typeId, PacketSerializer $in) : self{
		$recipeId = $in->getString();
		$template = $in->getRecipeIngredient();
		$input = $in->getRecipeIngredient();
		$addition = $in->getRecipeIngredient();
		$blockName = $in->getString();
		$recipeNetId = $in->readRecipeNetId();

		return new self(
			$typeId,
			$recipeId,
			$template,
			$input,
			$addition,
			$blockName,
			$recipeNetId
		);
	}

	public function encode(PacketSerializer $out) : void{
		$out->putString($this->recipeId);
		$out->putRecipeIngredient($this->template);
		$out->putRecipeIngredient($this->input);
		$out->putRecipeIngredient($this->addition);
		$out->putString($this->blockName);
		$out->writeRecipeNetId($this->recipeNetId);
	}
}
