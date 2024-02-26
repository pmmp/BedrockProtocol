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

namespace pocketmine\network\mcpe\protocol\types\inventory;

use function base64_encode;

final class ItemStack implements \JsonSerializable{
	/**
	 * @param string $rawExtraData Serialized ItemStackExtraData (use ItemStackExtraData->write())
	 * @see ItemStackExtraData::write()
	 */
	public function __construct(
		private int $id,
		private int $meta,
		private int $count,
		private int $blockRuntimeId,
		private string $rawExtraData,
	){}

	public static function null() : self{
		return new self(0, 0, 0, 0, "");
	}

	public function isNull() : bool{
		return $this->id === 0;
	}

	public function getId() : int{
		return $this->id;
	}

	public function getMeta() : int{
		return $this->meta;
	}

	public function getCount() : int{
		return $this->count;
	}

	public function getBlockRuntimeId() : int{ return $this->blockRuntimeId; }

	/**
	 * Decode this into ItemStackExtraData using ItemStackExtraData::read() (or ItemStackExtraDataShield::read() if this
	 * data is for a shield item)
	 * This isn't automatically decoded because it's usually not needed and is sometimes expensive to decode.
	 * @see ItemStackExtraData::read()
	 * @see ItemStackExtraDataShield::read()
	 */
	public function getRawExtraData() : string{ return $this->rawExtraData; }

	public function equals(ItemStack $itemStack) : bool{
		return $this->equalsWithoutCount($itemStack) && $this->count === $itemStack->count;
	}

	public function equalsWithoutCount(ItemStack $itemStack) : bool{
		return
			$this->id === $itemStack->id &&
			$this->meta === $itemStack->meta &&
			$this->blockRuntimeId === $itemStack->blockRuntimeId &&
			$this->rawExtraData === $itemStack->rawExtraData;
	}

	/** @return mixed[] */
	public function jsonSerialize() : array{
		return [
			"id" => $this->id,
			"meta" => $this->meta,
			"count" => $this->count,
			"blockRuntimeId" => $this->blockRuntimeId,
			"rawExtraData" => base64_encode($this->rawExtraData),
		];
	}
}
