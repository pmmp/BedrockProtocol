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

use pmmp\encoding\ByteBufferReader;
use pmmp\encoding\ByteBufferWriter;
use pmmp\encoding\VarInt;
use pocketmine\network\mcpe\protocol\serializer\CommonTypes;
use function count;

/**
 * Sets the message shown on the death screen underneath "You died!"
 */
class DeathInfoPacket extends DataPacket implements ClientboundPacket{
	public const NETWORK_ID = ProtocolInfo::DEATH_INFO_PACKET;

	private string $messageTranslationKey;
	/** @var string[] */
	private array $messageParameters;

	/**
	 * @generate-create-func
	 * @param string[] $messageParameters
	 */
	public static function create(string $messageTranslationKey, array $messageParameters) : self{
		$result = new self;
		$result->messageTranslationKey = $messageTranslationKey;
		$result->messageParameters = $messageParameters;
		return $result;
	}

	public function getMessageTranslationKey() : string{ return $this->messageTranslationKey; }

	/** @return string[] */
	public function getMessageParameters() : array{ return $this->messageParameters; }

	protected function decodePayload(ByteBufferReader $in) : void{
		$this->messageTranslationKey = CommonTypes::getString($in);

		$this->messageParameters = [];
		for($i = 0, $len = VarInt::readUnsignedInt($in); $i < $len; $i++){
			$this->messageParameters[] = CommonTypes::getString($in);
		}
	}

	protected function encodePayload(ByteBufferWriter $out) : void{
		CommonTypes::putString($out, $this->messageTranslationKey);

		VarInt::writeUnsignedInt($out, count($this->messageParameters));
		foreach($this->messageParameters as $parameter){
			CommonTypes::putString($out, $parameter);
		}
	}

	public function handle(PacketHandlerInterface $handler) : bool{
		return $handler->handleDeathInfo($this);
	}
}
