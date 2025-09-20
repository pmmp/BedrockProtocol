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
use pocketmine\network\mcpe\protocol\serializer\CommonTypes;

final class AttributeModifier{

	/**
	 * @see AttributeModifierOperation
	 * @see AttributeModifierTargetOperand
	 */
	public function __construct(
		private string $id,
		private string $name,
		private float $amount,
		private int $operation,
		private int $operand,
		private bool $serializable //???
	){}

	public function getId() : string{ return $this->id; }

	public function getName() : string{ return $this->name; }

	public function getAmount() : float{ return $this->amount; }

	public function getOperation() : int{ return $this->operation; }

	public function getOperand() : int{ return $this->operand; }

	public function isSerializable() : bool{ return $this->serializable; }

	public static function read(ByteBufferReader $in) : self{
		$id = CommonTypes::getString($in);
		$name = CommonTypes::getString($in);
		$amount = LE::readFloat($in);
		$operation = LE::readSignedInt($in);
		$operand = LE::readSignedInt($in);
		$serializable = CommonTypes::getBool($in);

		return new self($id, $name, $amount, $operation, $operand, $serializable);
	}

	public function write(ByteBufferWriter $out) : void{
		CommonTypes::putString($out, $this->id);
		CommonTypes::putString($out, $this->name);
		LE::writeFloat($out, $this->amount);
		LE::writeSignedInt($out, $this->operation);
		LE::writeSignedInt($out, $this->operand);
		CommonTypes::putBool($out, $this->serializable);
	}
}
