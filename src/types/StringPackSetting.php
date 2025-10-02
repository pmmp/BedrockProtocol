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

final class StringPackSetting extends PackSetting{
	public const ID = PackSettingType::STRING;

	private string $value;

	public function __construct(string $name, string $value){
		parent::__construct($name);
		$this->value = $value;
	}

	public function getValue() : string{
		return $this->value;
	}

	public function getTypeId() : PackSettingType{
		return self::ID;
	}

	public function write(ByteBufferWriter $out) : void{
		CommonTypes::putString($out, $this->value);
	}

	public static function read(ByteBufferReader $in, string $name) : self{
		return new self($name, CommonTypes::getString($in));
	}
}
