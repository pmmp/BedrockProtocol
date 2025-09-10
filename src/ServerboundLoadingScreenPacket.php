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
use pmmp\encoding\LE;
use pmmp\encoding\VarInt;
use pocketmine\network\mcpe\protocol\serializer\CommonTypes;
use pocketmine\network\mcpe\protocol\types\hud\LoadingScreenType;

class ServerboundLoadingScreenPacket extends DataPacket implements ServerboundPacket{
	public const NETWORK_ID = ProtocolInfo::SERVERBOUND_LOADING_SCREEN_PACKET;

	private LoadingScreenType $loadingScreenType;
	private ?int $loadingScreenId = null;

	/**
	 * @generate-create-func
	 */
	public static function create(LoadingScreenType $loadingScreenType, ?int $loadingScreenId) : self{
		$result = new self;
		$result->loadingScreenType = $loadingScreenType;
		$result->loadingScreenId = $loadingScreenId;
		return $result;
	}

	public function getLoadingScreenType() : LoadingScreenType{ return $this->loadingScreenType; }

	public function getLoadingScreenId() : ?int{ return $this->loadingScreenId; }

	protected function decodePayload(ByteBufferReader $in) : void{
		$this->loadingScreenType = LoadingScreenType::fromPacket(VarInt::readSignedInt($in));
		$this->loadingScreenId = CommonTypes::readOptional($in, LE::readUnsignedInt(...));
	}

	protected function encodePayload(ByteBufferWriter $out) : void{
		VarInt::writeSignedInt($out, $this->loadingScreenType->value);
		CommonTypes::writeOptional($out, $this->loadingScreenId, LE::writeUnsignedInt(...));
	}

	public function handle(PacketHandlerInterface $handler) : bool{
		return $handler->handleServerboundLoadingScreen($this);
	}
}
