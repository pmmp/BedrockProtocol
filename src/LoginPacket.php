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
use function count;
use function is_array;
use function is_string;
use function json_decode;
use function json_encode;
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
		try{
			$chainDataJson = json_decode($connRequestReader->get($chainDataJsonLength), associative: true, flags: JSON_THROW_ON_ERROR);
		}catch(\JsonException $e){
			throw new PacketDecodeException("Failed decoding chain data JSON: " . $e->getMessage());
		}
		if(!is_array($chainDataJson) || count($chainDataJson) !== 1 || !isset($chainDataJson["chain"])){
			throw new PacketDecodeException("Chain data must be a JSON object containing only the 'chain' element");
		}
		if(!is_array($chainDataJson["chain"])){
			throw new PacketDecodeException("Chain data 'chain' element must be a list of strings");
		}
		$jwts = [];
		foreach($chainDataJson["chain"] as $jwt){
			if(!is_string($jwt)){
				throw new PacketDecodeException("Chain data 'chain' must contain only strings");
			}
			$jwts[] = $jwt;
		}
		//TODO: this pointless JwtChain model is here for BC - get rid of it next chance we get
		$wrapper = new JwtChain;
		$wrapper->chain = $jwts;
		$this->chainDataJwt = $wrapper;

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
