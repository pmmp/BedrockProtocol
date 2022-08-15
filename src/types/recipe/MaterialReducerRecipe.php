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

final class MaterialReducerRecipe{
	/**
	 * @param MaterialReducerRecipeOutput[] $outputs
	 * @phpstan-param list<MaterialReducerRecipeOutput> $outputs
	 */
	public function __construct(
		private int $inputItemId,
		private int $inputItemMeta,
		private array $outputs
	){}

	public function getInputItemId() : int{ return $this->inputItemId; }

	public function getInputItemMeta() : int{ return $this->inputItemMeta; }

	/** @return MaterialReducerRecipeOutput[] */
	public function getOutputs() : array{ return $this->outputs; }
}
