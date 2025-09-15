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

use pmmp\encoding\ByteBufferReader;
use pmmp\encoding\ByteBufferWriter;
use pmmp\encoding\LE;
use pocketmine\network\mcpe\protocol\serializer\CommonTypes;
use pocketmine\network\mcpe\protocol\types\GetTypeIdFromConstTrait;

/**
 * Renames an item in an anvil, or map on a cartography table.
 */
final class CraftRecipeOptionalStackRequestAction extends ItemStackRequestAction{
	use GetTypeIdFromConstTrait;

	public const ID = ItemStackRequestActionType::CRAFTING_RECIPE_OPTIONAL;

	private int $recipeId;
	private int $filterStringIndex;

	//TODO: promote this when we can rename parameters (BC break)
	public function __construct(int $type, int $filterStringIndex){
		$this->recipeId = $type;
		$this->filterStringIndex = $filterStringIndex;
	}

	public function getRecipeId() : int{ return $this->recipeId; }

	public function getFilterStringIndex() : int{ return $this->filterStringIndex; }

	public static function read(ByteBufferReader $in) : self{
		$recipeId = CommonTypes::readRecipeNetId($in);
		$filterStringIndex = LE::readSignedInt($in);
		return new self($recipeId, $filterStringIndex);
	}

	public function write(ByteBufferWriter $out) : void{
		CommonTypes::writeRecipeNetId($out, $this->recipeId);
		LE::writeSignedInt($out, $this->filterStringIndex);
	}
}
