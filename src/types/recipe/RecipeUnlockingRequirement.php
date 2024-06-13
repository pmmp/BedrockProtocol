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
use function count;

final class RecipeUnlockingRequirement{

	/**
	 * @param RecipeIngredient[]|null $unlockingIngredients
	 */
	public function __construct(
		private ?array $unlockingIngredients
	){}

	/**
	 * @return RecipeIngredient[]|null
	 */
	public function getUnlockingIngredients() : ?array{ return $this->unlockingIngredients; }

	public static function read(PacketSerializer $in) : self{
		//I don't know what the point of this structure is. It could easily have been a list<RecipeIngredient> instead.
		//It's basically just an optional list, which could have been done by an empty list wherever it's not needed.
		$unlockingContext = $in->getBool();
		$unlockingIngredients = null;
		if(!$unlockingContext){
			$unlockingIngredients = [];
			for($i = 0, $count = $in->getUnsignedVarInt(); $i < $count; $i++){
				$unlockingIngredients[] = $in->getRecipeIngredient();
			}
		}

		return new self($unlockingIngredients);
	}

	public function write(PacketSerializer $out) : void{
		$out->putBool($this->unlockingIngredients === null);
		if($this->unlockingIngredients !== null){
			$out->putUnsignedVarInt(count($this->unlockingIngredients));
			foreach($this->unlockingIngredients as $ingredient){
				$out->putRecipeIngredient($ingredient);
			}
		}
	}
}
