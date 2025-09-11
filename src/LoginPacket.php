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

use pmmp\encoding\BE;
use pmmp\encoding\ByteBufferReader;
use pmmp\encoding\ByteBufferWriter;
use pmmp\encoding\LE;
use pocketmine\network\mcpe\protocol\serializer\CommonTypes;
use function strlen;

class LoginPacket extends DataPacket implements ServerboundPacket{
	public const NETWORK_ID = ProtocolInfo::LOGIN_PACKET;

	public int $protocol;
	public string $authInfoJson;
	public string $clientDataJwt;

	/**
	 * @generate-create-func
	 */
	public static function create(int $protocol, string $authInfoJson, string $clientDataJwt) : self{
		$result = new self;
		$result->protocol = $protocol;
		$result->authInfoJson = $authInfoJson;
		$result->clientDataJwt = $clientDataJwt;
		return $result;
	}

	public function canBeSentBeforeLogin() : bool{
		return true;
	}

	protected function decodePayload(ByteBufferReader $in) : void{
		$this->protocol = BE::readUnsignedInt($in);
		$this->decodeConnectionRequest(CommonTypes::getString($in));
	}

	protected function decodeConnectionRequest(string $binary) : void{
		$connRequestReader = new ByteBufferReader($binary);

		$authInfoJsonLength = LE::readUnsignedInt($connRequestReader);
		$this->authInfoJson = $connRequestReader->readByteArray($authInfoJsonLength);

		$clientDataJwtLength = LE::readUnsignedInt($connRequestReader);
		$this->clientDataJwt = $connRequestReader->readByteArray($clientDataJwtLength);
	}

	protected function encodePayload(ByteBufferWriter $out) : void{
		BE::writeUnsignedInt($out, $this->protocol);
		CommonTypes::putString($out, $this->encodeConnectionRequest());
	}

	protected function encodeConnectionRequest() : string{
		$connRequestWriter = new ByteBufferWriter();

		LE::writeUnsignedInt($connRequestWriter, strlen($this->authInfoJson));
		$connRequestWriter->writeByteArray($this->authInfoJson);

		LE::writeUnsignedInt($connRequestWriter, strlen($this->clientDataJwt));
		$connRequestWriter->writeByteArray($this->clientDataJwt);

		return $connRequestWriter->getData();
	}

	public function handle(PacketHandlerInterface $handler) : bool{
		return $handler->handleLogin($this);
	}
}
