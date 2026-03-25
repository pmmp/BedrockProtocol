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
use pocketmine\network\mcpe\protocol\serializer\CommonTypes;

/**
 * @see AttributeEnvironment
 */
final class AttributeValueBool extends AttributeValue{
	public const ID = AttributeValueType::BOOL;

	public const OPERATION_OVERRIDE = "override";
	public const OPERATION_ALPHA_BLEND = "alpha_blend";
	public const OPERATION_AND = "and";
	public const OPERATION_NAND = "nand";
	public const OPERATION_OR = "or";
	public const OPERATION_NOR = "nor";
	public const OPERATION_XOR = "xor";
	public const OPERATION_XNOR = "xnor";

	public function __construct(
		private bool $value,
		private string $operation,
	){}

	public function getTypeId() : int{
		return self::ID;
	}

	public function getValue() : bool{ return $this->value; }

	public function getOperation() : string{ return $this->operation; }

	public static function read(ByteBufferReader $in) : self{
		$value = CommonTypes::getBool($in);
		$operation = CommonTypes::getString($in);

		return new self(
			$value,
			$operation
		);
	}

	public function write(ByteBufferWriter $out) : void{
		CommonTypes::putBool($out, $this->value);
		CommonTypes::putString($out, $this->operation);
	}
}
