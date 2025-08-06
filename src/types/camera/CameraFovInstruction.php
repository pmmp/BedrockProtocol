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

final class CameraFovInstruction{

	/**
	 * @see CameraSetInstructionEaseType
	 */
	public function __construct(
		private float $fieldOfView,
		private float $easeTime,
		private int $easeType,
		private bool $clear,
	){}

	public function getFieldOfView() : float{ return $this->fieldOfView; }

	public function getEaseTime() : float{ return $this->easeTime; }

	/**
	 * @see CameraSetInstructionEaseType
	 */
	public function getEaseType() : int{ return $this->easeType; }

	public function getClear() : bool{ return $this->clear; }

	public static function read(PacketSerializer $in) : self{
		$fieldOfView = $in->getLFloat();
		$easeTime = $in->getLFloat();
		$easeType = $in->getByte();
		$clear = $in->getBool();
		return new self(
			$fieldOfView,
			$easeTime,
			$easeType,
			$clear
		);
	}

	public function write(PacketSerializer $out) : void{
		$out->putLFloat($this->fieldOfView);
		$out->putLFloat($this->easeTime);
		$out->putByte($this->easeType);
		$out->putBool($this->clear);
	}
}
