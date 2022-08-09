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

use pocketmine\network\mcpe\protocol\serializer\PacketSerializer;

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

	public static function read(PacketSerializer $in) : self{
		$id = $in->getString();
		$name = $in->getString();
		$amount = $in->getLFloat();
		$operation = $in->getLInt();
		$operand = $in->getLInt();
		$serializable = $in->getBool();

		return new self($id, $name, $amount, $operation, $operand, $serializable);
	}

	public function write(PacketSerializer $out) : void{
		$out->putString($this->id);
		$out->putString($this->name);
		$out->putLFloat($this->amount);
		$out->putLInt($this->operation);
		$out->putLInt($this->operand);
		$out->putBool($this->serializable);
	}
}
