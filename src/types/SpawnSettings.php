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
use pmmp\encoding\VarInt;
use pocketmine\network\mcpe\protocol\serializer\CommonTypes;

final class SpawnSettings{
	public const BIOME_TYPE_DEFAULT = 0;
	public const BIOME_TYPE_USER_DEFINED = 1;

	public function __construct(
		private int $biomeType,
		private string $biomeName,
		private int $dimension
	){}

	public function getBiomeType() : int{
		return $this->biomeType;
	}

	public function getBiomeName() : string{
		return $this->biomeName;
	}

	/**
	 * @see DimensionIds
	 */
	public function getDimension() : int{
		return $this->dimension;
	}

	public static function read(ByteBufferReader $in) : self{
		$biomeType = LE::readUnsignedShort($in);
		$biomeName = CommonTypes::getString($in);
		$dimension = VarInt::readSignedInt($in);

		return new self($biomeType, $biomeName, $dimension);
	}

	public function write(ByteBufferWriter $out) : void{
		LE::writeUnsignedShort($out, $this->biomeType);
		CommonTypes::putString($out, $this->biomeName);
		VarInt::writeSignedInt($out, $this->dimension);
	}
}
