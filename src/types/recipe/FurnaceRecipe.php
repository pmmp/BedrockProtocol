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

namespace pocketmine\network\mcpe\protocol\types\recipe;

use pmmp\encoding\ByteBufferReader;
use pmmp\encoding\ByteBufferWriter;
use pmmp\encoding\VarInt;
use pocketmine\network\mcpe\protocol\CraftingDataPacket;
use pocketmine\network\mcpe\protocol\serializer\CommonTypes;
use pocketmine\network\mcpe\protocol\types\inventory\ItemStack;

final class FurnaceRecipe extends RecipeWithTypeId{
	public function __construct(
		int $typeId,
		private int $inputId,
		private ?int $inputMeta,
		private ItemStack $result,
		private string $blockName
	){
		parent::__construct($typeId);
	}

	public function getInputId() : int{
		return $this->inputId;
	}

	public function getInputMeta() : ?int{
		return $this->inputMeta;
	}

	public function getResult() : ItemStack{
		return $this->result;
	}

	public function getBlockName() : string{
		return $this->blockName;
	}

	public static function decode(int $typeId, ByteBufferReader $in) : self{
		$inputId = VarInt::readSignedInt($in);
		$inputData = null;
		if($typeId === CraftingDataPacket::ENTRY_FURNACE_DATA){
			$inputData = VarInt::readSignedInt($in);
		}
		$output = CommonTypes::getItemStackWithoutStackId($in);
		$block = CommonTypes::getString($in);

		return new self($typeId, $inputId, $inputData, $output, $block);
	}

	public function encode(ByteBufferWriter $out) : void{
		VarInt::writeSignedInt($out, $this->inputId);
		if($this->getTypeId() === CraftingDataPacket::ENTRY_FURNACE_DATA){
			VarInt::writeSignedInt($out, $this->inputMeta);
		}
		CommonTypes::putItemStackWithoutStackId($out, $this->result);
		CommonTypes::putString($out, $this->blockName);
	}
}
