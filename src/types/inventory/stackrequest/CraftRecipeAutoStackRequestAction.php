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

namespace pocketmine\network\mcpe\protocol\types\inventory\stackrequest;

use pocketmine\network\mcpe\protocol\serializer\PacketSerializer;
use pocketmine\network\mcpe\protocol\types\GetTypeIdFromConstTrait;
use pocketmine\network\mcpe\protocol\types\recipe\RecipeIngredient;
use function count;

/**
 * Tells that the current transaction crafted the specified recipe, using the recipe book. This is effectively the same
 * as the regular crafting result action.
 */
final class CraftRecipeAutoStackRequestAction extends ItemStackRequestAction{
	use GetTypeIdFromConstTrait;

	public const ID = ItemStackRequestActionType::CRAFTING_RECIPE_AUTO;

	/**
	 * @param RecipeIngredient[] $ingredients
	 * @phpstan-param list<RecipeIngredient> $ingredients
	 */
	final public function __construct(
		private int $recipeId,
		private int $repetitions,
		private array $ingredients
	){}

	public function getRecipeId() : int{ return $this->recipeId; }

	public function getRepetitions() : int{ return $this->repetitions; }

	/**
	 * @return RecipeIngredient[]
	 * @phpstan-return list<RecipeIngredient>
	 */
	public function getIngredients() : array{ return $this->ingredients; }

	public static function read(PacketSerializer $in) : self{
		$recipeId = $in->readRecipeNetId();
		$repetitions = $in->getByte();
		$ingredients = [];
		for($i = 0, $count = $in->getByte(); $i < $count; ++$i){
			$ingredients[] = $in->getRecipeIngredient();
		}
		return new self($recipeId, $repetitions, $ingredients);
	}

	public function write(PacketSerializer $out) : void{
		$out->writeRecipeNetId($this->recipeId);
		$out->putByte($this->repetitions);
		$out->putByte(count($this->ingredients));
		foreach($this->ingredients as $ingredient){
			$out->putRecipeIngredient($ingredient);
		}
	}
}
