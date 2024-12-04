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

final class CameraAimAssistCategoryBlockPriority{

	public function __construct(
		private string $identifier,
		private int $priority
	){}

	public function getIdentifier() : string{ return $this->identifier; }

	public function getPriority() : int{ return $this->priority; }

	public static function read(PacketSerializer $in) : self{
		$identifier = $in->getString();
		$priority = $in->getLInt();
		return new self(
			$identifier,
			$priority
		);
	}

	public function write(PacketSerializer $out) : void{
		$out->putString($this->identifier);
		$out->putLInt($this->priority);
	}
}
