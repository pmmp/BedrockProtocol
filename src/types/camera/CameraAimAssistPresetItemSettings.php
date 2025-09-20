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

use pmmp\encoding\ByteBufferReader;
use pmmp\encoding\ByteBufferWriter;
use pocketmine\network\mcpe\protocol\serializer\CommonTypes;

final class CameraAimAssistPresetItemSettings{

	public function __construct(
		private string $itemIdentifier,
		private string $categoryName,
	){}

	public function getItemIdentifier() : string{ return $this->itemIdentifier; }

	public function getCategoryName() : string{ return $this->categoryName; }

	public static function read(ByteBufferReader $in) : self{
		$itemIdentifier = CommonTypes::getString($in);
		$categoryName = CommonTypes::getString($in);
		return new self(
			$itemIdentifier,
			$categoryName
		);
	}

	public function write(ByteBufferWriter $out) : void{
		CommonTypes::putString($out, $this->itemIdentifier);
		CommonTypes::putString($out, $this->categoryName);
	}
}
