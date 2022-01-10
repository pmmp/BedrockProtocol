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

namespace pocketmine\network\mcpe\protocol\types;

use pocketmine\color\Color;

class MapDecoration{

	private int $icon;
	private int $rotation;
	private int $xOffset;
	private int $yOffset;
	private string $label;
	private Color $color;

	public function __construct(int $icon, int $rotation, int $xOffset, int $yOffset, string $label, Color $color){
		$this->icon = $icon;
		$this->rotation = $rotation;
		$this->xOffset = $xOffset;
		$this->yOffset = $yOffset;
		$this->label = $label;
		$this->color = $color;
	}

	public function getIcon() : int{
		return $this->icon;
	}

	public function getRotation() : int{
		return $this->rotation;
	}

	public function getXOffset() : int{
		return $this->xOffset;
	}

	public function getYOffset() : int{
		return $this->yOffset;
	}

	public function getLabel() : string{
		return $this->label;
	}

	public function getColor() : Color{
		return $this->color;
	}
}
