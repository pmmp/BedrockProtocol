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
use pocketmine\network\mcpe\protocol\serializer\CommonTypes;
use pocketmine\network\mcpe\protocol\types\ShowStoreOfferRedirectType;
use Ramsey\Uuid\UuidInterface;

class ShowStoreOfferPacket extends DataPacket implements ClientboundPacket{
	public const NETWORK_ID = ProtocolInfo::SHOW_STORE_OFFER_PACKET;

	public UuidInterface $offerId;
	public ShowStoreOfferRedirectType $redirectType;

	/**
	 * @generate-create-func
	 */
	public static function create(UuidInterface $offerId, ShowStoreOfferRedirectType $redirectType) : self{
		$result = new self;
		$result->offerId = $offerId;
		$result->redirectType = $redirectType;
		return $result;
	}

	protected function decodePayload(ByteBufferReader $in) : void{
		$this->offerId = CommonTypes::getUUID($in);
		$this->redirectType = ShowStoreOfferRedirectType::fromPacket(Byte::readUnsigned($in));
	}

	protected function encodePayload(ByteBufferWriter $out) : void{
		CommonTypes::putUUID($out, $this->offerId);
		Byte::writeUnsigned($out, $this->redirectType->value);
	}

	public function handle(PacketHandlerInterface $handler) : bool{
		return $handler->handleShowStoreOffer($this);
	}
}
