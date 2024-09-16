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

use pocketmine\math\Vector3;
use pocketmine\network\mcpe\protocol\serializer\PacketSerializer;

final class CameraTargetInstruction{

	public function __construct(
		private ?Vector3 $targetCenterOffset,
		private int $targetActorUniqueId //This is a little-endian long, NOT a var-long. (WTF Mojang)
	){}

	public function getTargetCenterOffset() : ?Vector3{ return $this->targetCenterOffset; }

	public function getTargetActorUniqueId() : int{ return $this->targetActorUniqueId; }

	public static function read(PacketSerializer $in) : self{
		$targetCenterOffset = $in->readOptional(fn() => $in->getVector3());
		$targetActorUniqueId = $in->getLLong(); //WHY IS THIS NON-STANDARD?
		return new self(
			$targetCenterOffset,
			$targetActorUniqueId
		);
	}

	public function write(PacketSerializer $out) : void{
		$out->writeOptional($this->targetCenterOffset, fn(Vector3 $v) => $out->putVector3($v));
		$out->putLLong($this->targetActorUniqueId);
	}
}
