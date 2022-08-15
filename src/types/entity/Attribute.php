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

namespace pocketmine\network\mcpe\protocol\types\entity;

final class Attribute{
	/**
	 * @param AttributeModifier[] $modifiers
	 */
	public function __construct(
		private string $id,
		private float $min,
		private float $max,
		private float $current,
		private float $default,
		private array $modifiers
	){}

	public function getId() : string{
		return $this->id;
	}

	public function getMin() : float{
		return $this->min;
	}

	public function getMax() : float{
		return $this->max;
	}

	public function getCurrent() : float{
		return $this->current;
	}

	public function getDefault() : float{
		return $this->default;
	}

	/**
	 * @return AttributeModifier[]
	 */
	public function getModifiers() : array{ return $this->modifiers; }
}
