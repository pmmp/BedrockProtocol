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

final class CameraSetInstructionEase{

	/**
	 * @see CameraSetInstructionEaseType
	 */
	public function __construct(
		private int $type,
		private float $duration
	){}

	/**
	 * @see CameraSetInstructionEaseType
	 */
	public function getType() : int{ return $this->type; }

	public function getDuration() : float{ return $this->duration; }

	public static function read(PacketSerializer $in) : self{
		$type = $in->getByte();
		$duration = $in->getLFloat();
		return new self($type, $duration);
	}

	public function write(PacketSerializer $out) : void{
		$out->putByte($this->type);
		$out->putLFloat($this->duration);
	}
}
