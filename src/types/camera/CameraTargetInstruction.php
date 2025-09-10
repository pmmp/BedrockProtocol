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
use pocketmine\math\Vector3;
use pocketmine\network\mcpe\protocol\serializer\CommonTypes;

final class CameraTargetInstruction{

	public function __construct(
		private ?Vector3 $targetCenterOffset,
		private int $actorUniqueId
	){}

	public function getTargetCenterOffset() : ?Vector3{ return $this->targetCenterOffset; }

	public function getActorUniqueId() : int{ return $this->actorUniqueId; }

	public static function read(ByteBufferReader $in) : self{
		$targetCenterOffset = CommonTypes::readOptional($in, CommonTypes::getVector3(...));
		$actorUniqueId = LE::readSignedLong($in); //why be consistent mojang ?????
		return new self(
			$targetCenterOffset,
			$actorUniqueId
		);
	}

	public function write(ByteBufferWriter $out) : void{
		CommonTypes::writeOptional($out, $this->targetCenterOffset, CommonTypes::putVector3(...));
		LE::writeSignedLong($out, $this->actorUniqueId);
	}
}
