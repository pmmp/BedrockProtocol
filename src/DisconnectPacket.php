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

class DisconnectPacket extends DataPacket implements ClientboundPacket, ServerboundPacket{
	public const NETWORK_ID = ProtocolInfo::DISCONNECT_PACKET;

	public int $reason; //TODO: add constants / enum
	public ?string $message;
	public ?string $filteredMessage;

	/**
	 * @generate-create-func
	 */
	public static function create(int $reason, ?string $message, ?string $filteredMessage) : self{
		$result = new self;
		$result->reason = $reason;
		$result->message = $message;
		$result->filteredMessage = $filteredMessage;
		return $result;
	}

	public function canBeSentBeforeLogin() : bool{
		return true;
	}

	protected function decodePayload(PacketSerializer $in) : void{
		$this->reason = $in->getVarInt();
		$skipMessage = $in->getBool();
		$this->message = $skipMessage ? null : $in->getString();
		$this->filteredMessage = $skipMessage ? null : $in->getString();
	}

	protected function encodePayload(PacketSerializer $out) : void{
		$out->putVarInt($this->reason);
		$out->putBool($skipMessage = $this->message === null && $this->filteredMessage === null);
		if(!$skipMessage){
			$out->putString($this->message ?? "");
			$out->putString($this->filteredMessage ?? "");
		}
	}

	public function handle(PacketHandlerInterface $handler) : bool{
		return $handler->handleDisconnect($this);
	}
}
