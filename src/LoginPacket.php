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

use pocketmine\network\mcpe\protocol\serializer\PacketSerializer;
use pocketmine\network\mcpe\protocol\types\login\JwtChain;
use pocketmine\utils\BinaryStream;
use function is_object;
use function json_decode;
use function json_encode;
use function json_last_error_msg;
use function strlen;
use const JSON_THROW_ON_ERROR;

class LoginPacket extends DataPacket implements ServerboundPacket{
	public const NETWORK_ID = ProtocolInfo::LOGIN_PACKET;

	public int $protocol;
	public JwtChain $chainDataJwt;
	public string $clientDataJwt;

	/**
	 * @generate-create-func
	 */
	public static function create(int $protocol, JwtChain $chainDataJwt, string $clientDataJwt) : self{
		$result = new self;
		$result->protocol = $protocol;
		$result->chainDataJwt = $chainDataJwt;
		$result->clientDataJwt = $clientDataJwt;
		return $result;
	}

	public function canBeSentBeforeLogin() : bool{
		return true;
	}

	protected function decodePayload(PacketSerializer $in) : void{
		$this->protocol = $in->getInt();
		$this->decodeConnectionRequest($in->getString());
	}

	protected function decodeConnectionRequest(string $binary) : void{
		$connRequestReader = new BinaryStream($binary);

		$chainDataJsonLength = $connRequestReader->getLInt();
		if($chainDataJsonLength <= 0){
			//technically this is always positive; the problem results because getLInt() is implicitly signed
			//this is inconsistent with many other methods, but we can't do anything about that for now
			throw new PacketDecodeException("Length of chain data JSON must be positive");
		}
		$chainDataJson = json_decode($connRequestReader->get($chainDataJsonLength));
		if(!is_object($chainDataJson)){
			throw new PacketDecodeException("Failed decoding chain data JSON: " . json_last_error_msg());
		}
		$mapper = new \JsonMapper;
		$mapper->bExceptionOnMissingData = true;
		$mapper->bExceptionOnUndefinedProperty = true;
		try{
			$chainData = $mapper->map($chainDataJson, new JwtChain);
		}catch(\JsonMapper_Exception $e){
			throw PacketDecodeException::wrap($e);
		}

		$this->chainDataJwt = $chainData;
		$clientDataJwtLength = $connRequestReader->getLInt();
		if($clientDataJwtLength <= 0){
			//technically this is always positive; the problem results because getLInt() is implicitly signed
			//this is inconsistent with many other methods, but we can't do anything about that for now
			throw new PacketDecodeException("Length of clientData JWT must be positive");
		}
		$this->clientDataJwt = $connRequestReader->get($clientDataJwtLength);
	}

	protected function encodePayload(PacketSerializer $out) : void{
		$out->putInt($this->protocol);
		$out->putString($this->encodeConnectionRequest());
	}

	protected function encodeConnectionRequest() : string{
		$connRequestWriter = new BinaryStream();

		$chainDataJson = json_encode($this->chainDataJwt, JSON_THROW_ON_ERROR);
		$connRequestWriter->putLInt(strlen($chainDataJson));
		$connRequestWriter->put($chainDataJson);

		$connRequestWriter->putLInt(strlen($this->clientDataJwt));
		$connRequestWriter->put($this->clientDataJwt);

		return $connRequestWriter->getBuffer();
	}

	public function handle(PacketHandlerInterface $handler) : bool{
		return $handler->handleLogin($this);
	}
}
