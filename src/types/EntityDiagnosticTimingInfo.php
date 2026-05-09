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

use pmmp\encoding\Byte;
use pmmp\encoding\ByteBufferReader;
use pmmp\encoding\ByteBufferWriter;
use pmmp\encoding\LE;
use pocketmine\network\mcpe\protocol\serializer\CommonTypes;

final class EntityDiagnosticTimingInfo{

	public function __construct(
		private string $displayName,
		private string $entity,
		private int $timeInNS,
		private int $percentOfTotal,
	){}

	public function getDisplayName() : string{ return $this->displayName; }

	public function getEntity() : string{ return $this->entity; }

	public function getTimeInNS() : int{ return $this->timeInNS; }

	public function getPercentOfTotal() : int{ return $this->percentOfTotal; }

	public static function read(ByteBufferReader $in) : self{
		$displayName = CommonTypes::getString($in);
		$entity = CommonTypes::getString($in);
		$timeInNS = LE::readUnsignedLong($in);
		$percentOfTotal = Byte::readUnsigned($in);

		return new self(
			$displayName,
			$entity,
			$timeInNS,
			$percentOfTotal
		);
	}

	public function write(ByteBufferWriter $out) : void{
		CommonTypes::putString($out, $this->displayName);
		CommonTypes::putString($out, $this->entity);
		LE::writeUnsignedLong($out, $this->timeInNS);
		Byte::writeUnsigned($out, $this->percentOfTotal);
	}
}
