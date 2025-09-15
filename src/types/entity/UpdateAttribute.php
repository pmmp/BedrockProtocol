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

use pmmp\encoding\ByteBufferReader;
use pmmp\encoding\ByteBufferWriter;
use pmmp\encoding\LE;
use pmmp\encoding\VarInt;
use pocketmine\network\mcpe\protocol\serializer\CommonTypes;
use function count;

final class UpdateAttribute{
	/**
	 * @param AttributeModifier[] $modifiers
	 */
	public function __construct(
		private string $id,
		private float $min,
		private float $max,
		private float $current,
		private float $defaultMin,
		private float $defaultMax,
		private float $default,
		private array $modifiers
	){}

	public function getId() : string{ return $this->id; }

	public function getMin() : float{ return $this->min; }

	public function getMax() : float{ return $this->max; }

	public function getCurrent() : float{ return $this->current; }

	public function getDefaultMin() : float{ return $this->defaultMin; }

	public function getDefaultMax() : float{ return $this->defaultMax; }

	public function getDefault() : float{ return $this->default; }

	/**
	 * @return AttributeModifier[]
	 */
	public function getModifiers() : array{ return $this->modifiers; }

	public static function read(ByteBufferReader $in) : self{
		$min = LE::readFloat($in);
		$max = LE::readFloat($in);
		$current = LE::readFloat($in);
		$defaultMin = LE::readFloat($in);
		$defaultMax = LE::readFloat($in);
		$default = LE::readFloat($in);
		$id = CommonTypes::getString($in);

		$modifiers = [];
		for($j = 0, $modifierCount = VarInt::readUnsignedInt($in); $j < $modifierCount; $j++){
			$modifiers[] = AttributeModifier::read($in);
		}

		return new self($id, $min, $max, $current, $defaultMin, $defaultMax, $default, $modifiers);
	}

	public function write(ByteBufferWriter $out) : void{
		LE::writeFloat($out, $this->min);
		LE::writeFloat($out, $this->max);
		LE::writeFloat($out, $this->current);
		LE::writeFloat($out, $this->defaultMin);
		LE::writeFloat($out, $this->defaultMax);
		LE::writeFloat($out, $this->default);
		CommonTypes::putString($out, $this->id);

		VarInt::writeUnsignedInt($out, count($this->modifiers));
		foreach($this->modifiers as $modifier){
			$modifier->write($out);
		}
	}
}
