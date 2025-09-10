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
use pmmp\encoding\LE;
use pocketmine\network\mcpe\protocol\serializer\CommonTypes;

final class CameraAimAssistCategoryEntityPriority{

	public function __construct(
		private string $identifier,
		private int $priority
	){}

	public function getIdentifier() : string{ return $this->identifier; }

	public function getPriority() : int{ return $this->priority; }

	public static function read(ByteBufferReader $in) : self{
		$identifier = CommonTypes::getString($in);
		$priority = LE::readSignedInt($in);
		return new self(
			$identifier,
			$priority
		);
	}

	public function write(ByteBufferWriter $out) : void{
		CommonTypes::putString($out, $this->identifier);
		LE::writeSignedInt($out, $this->priority);
	}
}
