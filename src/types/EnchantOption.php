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
use pmmp\encoding\VarInt;
use pocketmine\network\mcpe\protocol\serializer\CommonTypes;
use function count;

final class EnchantOption{
	/**
	 * @param Enchant[] $equipActivatedEnchantments
	 * @param Enchant[] $heldActivatedEnchantments
	 * @param Enchant[] $selfActivatedEnchantments
	 */
	public function __construct(
		private int $cost,
		private int $slotFlags,
		private array $equipActivatedEnchantments,
		private array $heldActivatedEnchantments,
		private array $selfActivatedEnchantments,
		private string $name,
		private int $optionId
	){}

	public function getCost() : int{ return $this->cost; }

	public function getSlotFlags() : int{ return $this->slotFlags; }

	/** @return Enchant[] */
	public function getEquipActivatedEnchantments() : array{ return $this->equipActivatedEnchantments; }

	/** @return Enchant[] */
	public function getHeldActivatedEnchantments() : array{ return $this->heldActivatedEnchantments; }

	/** @return Enchant[] */
	public function getSelfActivatedEnchantments() : array{ return $this->selfActivatedEnchantments; }

	public function getName() : string{ return $this->name; }

	public function getOptionId() : int{ return $this->optionId; }

	/**
	 * @return Enchant[]
	 */
	private static function readEnchantList(ByteBufferReader $in) : array{
		$result = [];
		for($i = 0, $len = VarInt::readUnsignedInt($in); $i < $len; ++$i){
			$result[] = Enchant::read($in);
		}
		return $result;
	}

	/**
	 * @param Enchant[] $list
	 */
	private static function writeEnchantList(ByteBufferWriter $out, array $list) : void{
		VarInt::writeUnsignedInt($out, count($list));
		foreach($list as $item){
			$item->write($out);
		}
	}

	public static function read(ByteBufferReader $in) : self{
		$cost = VarInt::readUnsignedInt($in);

		$slotFlags = LE::readUnsignedInt($in);
		$equipActivatedEnchants = self::readEnchantList($in);
		$heldActivatedEnchants = self::readEnchantList($in);
		$selfActivatedEnchants = self::readEnchantList($in);

		$name = CommonTypes::getString($in);
		$optionId = CommonTypes::readRecipeNetId($in);

		return new self($cost, $slotFlags, $equipActivatedEnchants, $heldActivatedEnchants, $selfActivatedEnchants, $name, $optionId);
	}

	public function write(ByteBufferWriter $out) : void{
		VarInt::writeUnsignedInt($out, $this->cost);

		LE::writeUnsignedInt($out, $this->slotFlags);
		self::writeEnchantList($out, $this->equipActivatedEnchantments);
		self::writeEnchantList($out, $this->heldActivatedEnchantments);
		self::writeEnchantList($out, $this->selfActivatedEnchantments);

		CommonTypes::putString($out, $this->name);
		CommonTypes::writeRecipeNetId($out, $this->optionId);
	}
}
