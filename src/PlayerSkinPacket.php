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
use pocketmine\network\mcpe\protocol\serializer\CommonTypes;
use pocketmine\network\mcpe\protocol\types\skin\SkinData;
use Ramsey\Uuid\UuidInterface;

class PlayerSkinPacket extends DataPacket implements ClientboundPacket, ServerboundPacket{
	public const NETWORK_ID = ProtocolInfo::PLAYER_SKIN_PACKET;

	public UuidInterface $uuid;
	public string $oldSkinName = "";
	public string $newSkinName = "";
	public SkinData $skin;

	/**
	 * @generate-create-func
	 */
	public static function create(UuidInterface $uuid, string $oldSkinName, string $newSkinName, SkinData $skin) : self{
		$result = new self;
		$result->uuid = $uuid;
		$result->oldSkinName = $oldSkinName;
		$result->newSkinName = $newSkinName;
		$result->skin = $skin;
		return $result;
	}

	protected function decodePayload(ByteBufferReader $in) : void{
		$this->uuid = CommonTypes::getUUID($in);
		$this->skin = CommonTypes::getSkin($in);
		$this->newSkinName = CommonTypes::getString($in);
		$this->oldSkinName = CommonTypes::getString($in);
		$this->skin->setVerified(CommonTypes::getBool($in));
	}

	protected function encodePayload(ByteBufferWriter $out) : void{
		CommonTypes::putUUID($out, $this->uuid);
		CommonTypes::putSkin($out, $this->skin);
		CommonTypes::putString($out, $this->newSkinName);
		CommonTypes::putString($out, $this->oldSkinName);
		CommonTypes::putBool($out, $this->skin->isVerified());
	}

	public function handle(PacketHandlerInterface $handler) : bool{
		return $handler->handlePlayerSkin($this);
	}
}
