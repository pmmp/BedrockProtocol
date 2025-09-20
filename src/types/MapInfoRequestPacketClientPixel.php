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

use pmmp\encoding\ByteBufferReader;
use pmmp\encoding\ByteBufferWriter;
use pmmp\encoding\LE;
use pocketmine\color\Color;
use function intdiv;

final class MapInfoRequestPacketClientPixel{

	private const Y_INDEX_MULTIPLIER = 128;

	public function __construct(
		public Color $color,
		public int $x,
		public int $y
	){}

	public function getColor() : Color{ return $this->color; }

	public function getX() : int{ return $this->x; }

	public function getY() : int{ return $this->y; }

	public static function read(ByteBufferReader $in) : self{
		$color = LE::readUnsignedInt($in);
		$index = LE::readUnsignedShort($in);

		$x = $index % self::Y_INDEX_MULTIPLIER;
		$y = intdiv($index, self::Y_INDEX_MULTIPLIER);

		return new self(Color::fromRGBA($color), $x, $y);
	}

	public function write(ByteBufferWriter $out) : void{
		LE::writeUnsignedInt($out, $this->color->toRGBA());
		LE::writeUnsignedShort($out, $this->x + ($this->y * self::Y_INDEX_MULTIPLIER));
	}
}
