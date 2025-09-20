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
use pmmp\encoding\BE;
use pmmp\encoding\ByteBufferReader;
use pmmp\encoding\ByteBufferWriter;
use pmmp\encoding\LE;
use pmmp\encoding\VarInt;
use pocketmine\network\mcpe\protocol\serializer\CommonTypes;
use function strlen;

class LoginPacketTest extends TestCase{

	public function testInvalidChainDataJsonHandling() : void{
		$stream = new ByteBufferWriter();
		VarInt::writeUnsignedInt($stream, ProtocolInfo::LOGIN_PACKET);
		BE::writeUnsignedInt($stream, ProtocolInfo::CURRENT_PROTOCOL);

		$payload = '{"chain":[]'; //intentionally malformed
		$stream2 = new ByteBufferWriter();
		LE::writeUnsignedInt($stream2, strlen($payload));
		$stream2->writeByteArray($payload);

		CommonTypes::putString($stream, $stream2->getData());

		$pk = PacketPool::getInstance()->getPacket($stream->getData());
		self::assertInstanceOf(LoginPacket::class, $pk);

		$this->expectException(PacketDecodeException::class);
		$pk->decode(new ByteBufferReader($stream->getData())); //bang
	}
}
