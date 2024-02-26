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

namespace pocketmine\network\mcpe\protocol;

use PHPUnit\Framework\TestCase;

final class ProtocolInfoTest extends TestCase{

	public function testMinecraftVersionNetwork() : void{
		self::assertMatchesRegularExpression(
			'/^(?:\d+\.)?(?:\d+\.)?(?:\d+\.)?\d+$/',
			ProtocolInfo::MINECRAFT_VERSION_NETWORK,
			"Network version should only contain 0-9 and \".\", and no more than 4 groups of digits"
		);
	}
}
