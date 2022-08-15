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

namespace pocketmine\network\mcpe\protocol\types\skin;

class SkinAnimation{

	public const TYPE_HEAD = 1;
	public const TYPE_BODY_32 = 2;
	public const TYPE_BODY_64 = 3;

	public const EXPRESSION_LINEAR = 0; //???
	public const EXPRESSION_BLINKING = 1;

	public function __construct(
		private SkinImage $image,
		private int $type,
		private float $frames,
		private int $expressionType
	){}

	/**
	 * Image of the animation.
	 */
	public function getImage() : SkinImage{
		return $this->image;
	}

	/**
	 * The type of animation you are applying.
	 */
	public function getType() : int{
		return $this->type;
	}

	/**
	 * The total amount of frames in an animation.
	 */
	public function getFrames() : float{
		return $this->frames;
	}

	public function getExpressionType() : int{ return $this->expressionType; }
}
