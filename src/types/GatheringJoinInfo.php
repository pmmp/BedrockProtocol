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

final class GatheringJoinInfo{

	public function __construct(
		private string $experienceId,
		private string $experienceName,
		private string $experienceWorldId,
		private string $experienceWorldName,
		private string $creatorId,
		private string $storeId,
	){}

	public function getExperienceId() : string{ return $this->experienceId; }

	public function getExperienceName() : string{ return $this->experienceName; }

	public function getExperienceWorldId() : string{ return $this->experienceWorldId; }

	public function getExperienceWorldName() : string{ return $this->experienceWorldName; }

	public function getCreatorId() : string{ return $this->creatorId; }

	public function getStoreId() : string{ return $this->storeId; }

	public static function read(ByteBufferReader $in) : self{
		$experienceId = CommonTypes::getString($in);
		$experienceName = CommonTypes::getString($in);
		$experienceWorldId = CommonTypes::getString($in);
		$experienceWorldName = CommonTypes::getString($in);
		$creatorId = CommonTypes::getString($in);
		$storeId = CommonTypes::getString($in);

		return new self(
			$experienceId,
			$experienceName,
			$experienceWorldId,
			$experienceWorldName,
			$creatorId,
			$storeId
		);
	}

	public function write(ByteBufferWriter $out) : void{
		CommonTypes::putString($out, $this->experienceId);
		CommonTypes::putString($out, $this->experienceName);
		CommonTypes::putString($out, $this->experienceWorldId);
		CommonTypes::putString($out, $this->experienceWorldName);
		CommonTypes::putString($out, $this->creatorId);
		CommonTypes::putString($out, $this->storeId);
	}
}
