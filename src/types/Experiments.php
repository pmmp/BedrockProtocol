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

	public static function read(ByteBufferReader $in) : self{
		$experiments = [];
		for($i = 0, $len = LE::readUnsignedInt($in); $i < $len; ++$i){
			$experimentName = CommonTypes::getString($in);
			$enabled = CommonTypes::getBool($in);
			$experiments[$experimentName] = $enabled;
		}
		$hasPreviouslyUsedExperiments = CommonTypes::getBool($in);
		return new self($experiments, $hasPreviouslyUsedExperiments);
	}

	public function write(ByteBufferWriter $out) : void{
		LE::writeUnsignedInt($out, count($this->experiments));
		foreach($this->experiments as $experimentName => $enabled){
			CommonTypes::putString($out, $experimentName);
			CommonTypes::putBool($out, $enabled);
		}
		CommonTypes::putBool($out, $this->hasPreviouslyUsedExperiments);
	}
}
