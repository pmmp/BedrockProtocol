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
use pocketmine\network\mcpe\protocol\serializer\PacketSerializer;

class DataPacketTest extends TestCase{

	public function testHeaderFidelity() : void{
		$pk = new TestPacket();
		$pk->senderSubId = 3;
		$pk->recipientSubId = 2;

		$serializer = PacketSerializer::encoder();
		$pk->encode($serializer);

		$pk2 = new TestPacket();
		$pk2->decode(PacketSerializer::decoder($serializer->getBuffer(), 0));
		self::assertSame($pk2->senderSubId, 3);
		self::assertSame($pk2->recipientSubId, 2);
	}
}
