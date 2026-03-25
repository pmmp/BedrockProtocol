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
use pocketmine\network\mcpe\protocol\PacketDecodeException;
use pocketmine\network\mcpe\protocol\serializer\CommonTypes;

/**
 * @see AttributeEnvironment
 */
final class AttributeValueColor extends AttributeValue{
	public const ID = AttributeValueType::COLOR;

	public const OPERATION_OVERRIDE = "override";
	public const OPERATION_ALPHA_BLEND = "alpha_blend";
	public const OPERATION_ADD = "add";
	public const OPERATION_SUBTRACT = "subtract";
	public const OPERATION_MULTIPLY = "multiply";

	public function __construct(
		private AttributeValueColorValue $value,
		private string $operation,
	){}

	public function getTypeId() : int{
		return self::ID;
	}

	public function getValue() : AttributeValueColorValue{ return $this->value; }

	public function getOperation() : string{ return $this->operation; }

	public static function read(ByteBufferReader $in) : self{
		$value = match(VarInt::readUnsignedInt($in)){
			AttributeValueColorArray::ID => AttributeValueColorArray::read($in),
			AttributeValueColorString::ID => AttributeValueColorString::read($in),
			default => throw new PacketDecodeException("Unknown AttributeValueColor type"),
		};
		$operation = CommonTypes::getString($in);

		return new self(
			$value,
			$operation
		);
	}

	public function write(ByteBufferWriter $out) : void{
		VarInt::writeUnsignedInt($out, $this->value->getTypeId());
		$this->value->write($out);
		CommonTypes::putString($out, $this->operation);
	}
}
