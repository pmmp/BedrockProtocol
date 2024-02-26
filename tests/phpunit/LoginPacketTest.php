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
use function strlen;

class LoginPacketTest extends TestCase{

	public function testInvalidChainDataJsonHandling() : void{
		$stream = PacketSerializer::encoder();
		$stream->putUnsignedVarInt(ProtocolInfo::LOGIN_PACKET);
		$payload = '{"chain":[]'; //intentionally malformed
		$stream->putInt(ProtocolInfo::CURRENT_PROTOCOL);

		$stream2 = PacketSerializer::encoder();
		$stream2->putLInt(strlen($payload));
		$stream2->put($payload);
		$stream->putString($stream2->getBuffer());

		$pk = PacketPool::getInstance()->getPacket($stream->getBuffer());
		self::assertInstanceOf(LoginPacket::class, $pk);

		$this->expectException(PacketDecodeException::class);
		$pk->decode(PacketSerializer::decoder($stream->getBuffer(), 0)); //bang
	}
}
