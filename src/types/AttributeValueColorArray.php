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

/**
 * @see AttributeValueColor
 */
final class AttributeValueColorArray extends AttributeValueColorValue{
	public const ID = AttributeValueColorType::ARRAY;

	public function __construct(
		private Color $value
	){}

	public function getTypeId() : int{
		return self::ID;
	}

	public function getValue() : Color{ return $this->value; }

	public static function read(ByteBufferReader $in) : self{
		$r = LE::readUnsignedInt($in);
		$g = LE::readUnsignedInt($in);
		$b = LE::readUnsignedInt($in);
		$a = LE::readUnsignedInt($in);

		return new self(
			new Color($r, $g, $b, $a)
		);
	}

	public function write(ByteBufferWriter $out) : void{
		LE::writeUnsignedInt($out, $this->value->getR());
		LE::writeUnsignedInt($out, $this->value->getG());
		LE::writeUnsignedInt($out, $this->value->getB());
		LE::writeUnsignedInt($out, $this->value->getA());
	}
}
