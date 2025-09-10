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

final class FeatureRegistryPacketEntry{

	public function __construct(
		private string $featureName,
		private string $featureJson
	){}

	public function getFeatureName() : string{ return $this->featureName; }

	public function getFeatureJson() : string{ return $this->featureJson; }

	public static function read(ByteBufferReader $in) : self{
		$featureName = CommonTypes::getString($in);
		$featureJson = CommonTypes::getString($in);

		return new self($featureName, $featureJson);
	}

	public function write(ByteBufferWriter $out) : void{
		CommonTypes::putString($out, $this->featureName);
		CommonTypes::putString($out, $this->featureJson);
	}
}
