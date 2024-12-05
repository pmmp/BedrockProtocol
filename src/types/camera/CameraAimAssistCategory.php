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

final class CameraAimAssistCategory{

	public function __construct(
		private string $name,
		private CameraAimAssistCategoryPriorities $priorities
	){}

	public function getName() : string{ return $this->name; }

	public function getPriorities() : CameraAimAssistCategoryPriorities{ return $this->priorities; }

	public static function read(PacketSerializer $in) : self{
		$name = $in->getString();
		$priorities = CameraAimAssistCategoryPriorities::read($in);
		return new self(
			$name,
			$priorities
		);
	}

	public function write(PacketSerializer $out) : void{
		$out->putString($this->name);
		$this->priorities->write($out);
	}
}
