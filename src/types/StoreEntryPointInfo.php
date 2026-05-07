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

final class StoreEntryPointInfo{

	public function __construct(
		private string $id,
		private string $name,
	){}

	public function getId() : string{ return $this->id; }

	public function getName() : string{ return $this->name; }

	public static function read(ByteBufferReader $in) : self{
		$storeId = CommonTypes::getString($in);
		$storeName = CommonTypes::getString($in);

		return new self(
			$storeId,
			$storeName,
		);
	}

	public function write(ByteBufferWriter $out) : void{
		CommonTypes::putString($out, $this->id);
		CommonTypes::putString($out, $this->name);
	}
}
