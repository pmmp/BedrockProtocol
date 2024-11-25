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

namespace pocketmine\network\mcpe\protocol\types\camera;

use pocketmine\network\mcpe\protocol\serializer\PacketSerializer;
use function count;

final class CameraAimAssistCategories{

	/**
	 * @param CameraAimAssistCategory[] $categories
	 */
	public function __construct(
		private string $identifier,
		private array $categories
	){}

	public function getIdentifier() : string{ return $this->identifier; }

	/**
	 * @return CameraAimAssistCategory[]
	 */
	public function getCategories() : array{ return $this->categories; }

	public static function read(PacketSerializer $in) : self{
		$identifier = $in->getString();

		$categories = [];
		for($i = 0, $len = $in->getUnsignedVarInt(); $i < $len; ++$i){
			$categories[] = CameraAimAssistCategory::read($in);
		}

		return new self(
			$identifier,
			$categories
		);
	}

	public function write(PacketSerializer $out) : void{
		$out->putString($this->identifier);
		$out->putUnsignedVarInt(count($this->categories));
		foreach($this->categories as $category){
			$category->write($out);
		}
	}
}
