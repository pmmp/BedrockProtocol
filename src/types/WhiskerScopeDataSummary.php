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
use pmmp\encoding\LE;
use pocketmine\network\mcpe\protocol\serializer\CommonTypes;

final class WhiskerScopeDataSummary{

	public function __construct(
		private string $label,
		private string $indentation,
		private int $totalHighCostNS,
		private int $totalMidCostNS,
		private int $totalLowCostNS,
	){}

	public function getLabel() : string{ return $this->label; }

	public function getIndentation() : string{ return $this->indentation; }

	public function getTotalHighCostNS() : int{ return $this->totalHighCostNS; }

	public function getTotalMidCostNS() : int{ return $this->totalMidCostNS; }

	public function getTotalLowCostNS() : int{ return $this->totalLowCostNS; }

	public static function read(ByteBufferReader $in) : self{
		$label = CommonTypes::getString($in);
		$indentation = CommonTypes::getString($in);
		$totalHighCostNS = LE::readUnsignedLong($in);
		$totalMidCostNS = LE::readUnsignedLong($in);
		$totalLowCostNS = LE::readUnsignedLong($in);

		return new self(
			$label,
			$indentation,
			$totalHighCostNS,
			$totalMidCostNS,
			$totalLowCostNS
		);
	}

	public function write(ByteBufferWriter $out) : void{
		CommonTypes::putString($out, $this->label);
		CommonTypes::putString($out, $this->indentation);
		LE::writeUnsignedLong($out, $this->totalHighCostNS);
		LE::writeUnsignedLong($out, $this->totalMidCostNS);
		LE::writeUnsignedLong($out, $this->totalLowCostNS);
	}
}
