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

use pmmp\encoding\ByteBufferReader;
use pmmp\encoding\ByteBufferWriter;
use pocketmine\network\mcpe\protocol\serializer\CommonTypes;

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

	public static function decode(int $typeId, ByteBufferReader $in) : self{
		$recipeId = CommonTypes::getString($in);
		$template = CommonTypes::getRecipeIngredient($in);
		$input = CommonTypes::getRecipeIngredient($in);
		$addition = CommonTypes::getRecipeIngredient($in);
		$blockName = CommonTypes::getString($in);
		$recipeNetId = CommonTypes::readRecipeNetId($in);

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

	public function encode(ByteBufferWriter $out) : void{
		CommonTypes::putString($out, $this->recipeId);
		CommonTypes::putRecipeIngredient($out, $this->template);
		CommonTypes::putRecipeIngredient($out, $this->input);
		CommonTypes::putRecipeIngredient($out, $this->addition);
		CommonTypes::putString($out, $this->blockName);
		CommonTypes::writeRecipeNetId($out, $this->recipeNetId);
	}
}
