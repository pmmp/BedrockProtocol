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
use function is_int;

final class CameraFovInstruction{

	/** @see CameraSetInstructionEaseType */
	private string $easeType;

	public function __construct(
		private float $fieldOfView,
		private float $easeTime,
		int|string $easeType,
		private bool $clear,
	){
		$this->easeType = is_int($easeType) ? CameraSetInstructionEaseType::toName($easeType) : $easeType;
	}

	public function getFieldOfView() : float{ return $this->fieldOfView; }

	public function getEaseTime() : float{ return $this->easeTime; }

	/**
	 * @see CameraSetInstructionEaseType
	 */
	public function getEaseType() : string{ return $this->easeType; }

	public function getClear() : bool{ return $this->clear; }

	public static function read(ByteBufferReader $in) : self{
		$fieldOfView = LE::readFloat($in);
		$easeTime = LE::readFloat($in);
		$easeType = CommonTypes::getString($in);
		$clear = CommonTypes::getBool($in);

		return new self(
			$fieldOfView,
			$easeTime,
			$easeType,
			$clear
		);
	}

	public function write(ByteBufferWriter $out) : void{
		LE::writeFloat($out, $this->fieldOfView);
		LE::writeFloat($out, $this->easeTime);
		CommonTypes::putString($out, $this->easeType);
		CommonTypes::putBool($out, $this->clear);
	}
}
