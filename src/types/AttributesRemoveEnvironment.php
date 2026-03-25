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
 * @see ClientboundAttributeLayerSyncPacket
 */
final class AttributesRemoveEnvironment extends AttributeLayerSyncPayload{
	public const ID = AttributeLayerSyncType::REMOVE_ENVIRONMENT;

	/**
	 * @param string[] $attributes
	 * @phpstan-param list<string> $attributes
	 */
	public function __construct(
		private string $name,
		private int $dimension,
		private array $attributes,
	){}

	public function getTypeId() : int{
		return self::ID;
	}

	public function getName() : string{ return $this->name; }

	public function getDimension() : int{ return $this->dimension; }

	/**
	 * @return string[]
	 * @phpstan-return list<string>
	 */
	public function getAttributes() : array{ return $this->attributes; }

	public static function read(ByteBufferReader $in) : self{
		$name = CommonTypes::getString($in);
		$dimension = VarInt::readUnsignedInt($in);

		$attributes = [];
		for($i = 0, $len = VarInt::readUnsignedInt($in); $i < $len; ++$i){
			$attributes[] = CommonTypes::getString($in);
		}

		return new self(
			$name,
			$dimension,
			$attributes,
		);
	}

	public function write(ByteBufferWriter $out) : void{
		CommonTypes::putString($out, $this->name);
		VarInt::writeUnsignedInt($out, $this->dimension);

		VarInt::writeUnsignedInt($out, count($this->attributes));
		foreach($this->attributes as $attribute){
			CommonTypes::putString($out, $attribute);
		}
	}
}
