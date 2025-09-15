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

use pmmp\encoding\Byte;
use pmmp\encoding\ByteBufferReader;
use pmmp\encoding\ByteBufferWriter;
use pmmp\encoding\LE;
use pocketmine\network\mcpe\protocol\serializer\CommonTypes;

class PhotoTransferPacket extends DataPacket implements ClientboundPacket{
	public const NETWORK_ID = ProtocolInfo::PHOTO_TRANSFER_PACKET;

	public string $photoName;
	public string $photoData;
	public string $bookId; //photos are stored in a sibling directory to the games folder (screenshots/(some UUID)/bookID/example.png)
	public int $type;
	public int $sourceType;
	public int $ownerActorUniqueId;
	public string $newPhotoName; //???

	/**
	 * @generate-create-func
	 */
	public static function create(
		string $photoName,
		string $photoData,
		string $bookId,
		int $type,
		int $sourceType,
		int $ownerActorUniqueId,
		string $newPhotoName,
	) : self{
		$result = new self;
		$result->photoName = $photoName;
		$result->photoData = $photoData;
		$result->bookId = $bookId;
		$result->type = $type;
		$result->sourceType = $sourceType;
		$result->ownerActorUniqueId = $ownerActorUniqueId;
		$result->newPhotoName = $newPhotoName;
		return $result;
	}

	protected function decodePayload(ByteBufferReader $in) : void{
		$this->photoName = CommonTypes::getString($in);
		$this->photoData = CommonTypes::getString($in);
		$this->bookId = CommonTypes::getString($in);
		$this->type = Byte::readUnsigned($in);
		$this->sourceType = Byte::readUnsigned($in);
		$this->ownerActorUniqueId = LE::readSignedLong($in); //...............
		$this->newPhotoName = CommonTypes::getString($in);
	}

	protected function encodePayload(ByteBufferWriter $out) : void{
		CommonTypes::putString($out, $this->photoName);
		CommonTypes::putString($out, $this->photoData);
		CommonTypes::putString($out, $this->bookId);
		Byte::writeUnsigned($out, $this->type);
		Byte::writeUnsigned($out, $this->sourceType);
		LE::writeSignedLong($out, $this->ownerActorUniqueId);
		CommonTypes::putString($out, $this->newPhotoName);
	}

	public function handle(PacketHandlerInterface $handler) : bool{
		return $handler->handlePhotoTransfer($this);
	}
}
