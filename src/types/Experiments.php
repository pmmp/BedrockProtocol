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

use pocketmine\network\mcpe\protocol\serializer\PacketSerializer;
use function count;

final class Experiments{
	/**
	 * @param bool[] $experiments
	 * @phpstan-param array<string, bool> $experiments
	 */
	public function __construct(
		private array $experiments,
		private bool $hasPreviouslyUsedExperiments
	){}

	/** @return bool[] */
	public function getExperiments() : array{ return $this->experiments; }

	public function hasPreviouslyUsedExperiments() : bool{ return $this->hasPreviouslyUsedExperiments; }

	public static function read(PacketSerializer $in) : self{
		$experiments = [];
		for($i = 0, $len = $in->getLInt(); $i < $len; ++$i){
			$experimentName = $in->getString();
			$enabled = $in->getBool();
			$experiments[$experimentName] = $enabled;
		}
		$hasPreviouslyUsedExperiments = $in->getBool();
		return new self($experiments, $hasPreviouslyUsedExperiments);
	}

	public function write(PacketSerializer $out) : void{
		$out->putLInt(count($this->experiments));
		foreach($this->experiments as $experimentName => $enabled){
			$out->putString($experimentName);
			$out->putBool($enabled);
		}
		$out->putBool($this->hasPreviouslyUsedExperiments);
	}
}
