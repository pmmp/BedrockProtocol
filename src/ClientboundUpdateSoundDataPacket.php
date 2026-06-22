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
use pocketmine\network\mcpe\protocol\serializer\CommonTypes;

class ClientboundUpdateSoundDataPacket extends DataPacket implements ClientboundPacket{
	public const NETWORK_ID = ProtocolInfo::CLIENTBOUND_UPDATE_SOUND_DATA_PACKET;

	private int $serverSoundHandle;
	private string $soundEvent;

	/**
	 * @generate-create-func
	 */
	public static function create(int $serverSoundHandle, string $soundEvent) : self{
		$result = new self;
		$result->serverSoundHandle = $serverSoundHandle;
		$result->soundEvent = $soundEvent;
		return $result;
	}

	public function getServerSoundHandle() : int{ return $this->serverSoundHandle; }

	public function getSoundEvent() : string{ return $this->soundEvent; }

	protected function decodePayload(ByteBufferReader $in) : void{
		$this->serverSoundHandle = LE::readUnsignedLong($in);
		$this->soundEvent = CommonTypes::getString($in);
	}

	protected function encodePayload(ByteBufferWriter $out) : void{
		LE::writeUnsignedLong($out, $this->serverSoundHandle);
		CommonTypes::putString($out, $this->soundEvent);
	}

	public function handle(PacketHandlerInterface $handler) : bool{
		return $handler->handleClientboundUpdateSoundData($this);
	}
}
