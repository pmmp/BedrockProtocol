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
use pmmp\encoding\VarInt;
use pocketmine\network\mcpe\protocol\serializer\CommonTypes;
use function count;

/**
 * @see AttributeUpdateLayers
 */
final class AttributeLayer{

	/**
	 * @param AttributeEnvironment[] $attributes
	 * @phpstan-param list<AttributeEnvironment> $attributes
	 */
	public function __construct(
		private string $name,
		private int $dimension,
		private AttributeLayerSettings $settings,
		private array $attributes,
	){}

	public function getName() : string{ return $this->name; }

	public function getDimension() : int{ return $this->dimension; }

	public function getSettings() : AttributeLayerSettings{ return $this->settings; }

	/**
	 * @return AttributeEnvironment[]
	 * @phpstan-return list<AttributeEnvironment>
	 */
	public function getAttributes() : array{ return $this->attributes; }

	public static function read(ByteBufferReader $in) : self{
		$name = CommonTypes::getString($in);
		$dimension = VarInt::readUnsignedInt($in);
		$settings = AttributeLayerSettings::read($in);

		$attributes = [];
		for($i = 0, $len = VarInt::readUnsignedInt($in); $i < $len; ++$i){
			$attributes[] = AttributeEnvironment::read($in);
		}

		return new self(
			$name,
			$dimension,
			$settings,
			$attributes,
		);
	}

	public function write(ByteBufferWriter $out) : void{
		CommonTypes::putString($out, $this->name);
		VarInt::writeUnsignedInt($out, $this->dimension);
		$this->settings->write($out);

		VarInt::writeUnsignedInt($out, count($this->attributes));
		foreach($this->attributes as $attribute){
			$attribute->write($out);
		}
	}
}
