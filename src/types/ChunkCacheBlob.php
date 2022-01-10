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

class ChunkCacheBlob{

	private int $hash;
	private string $payload;

	/**
	 * ChunkCacheBlob constructor.
	 */
	public function __construct(int $hash, string $payload){
		$this->hash = $hash;
		$this->payload = $payload;
	}

	public function getHash() : int{
		return $this->hash;
	}

	public function getPayload() : string{
		return $this->payload;
	}
}
