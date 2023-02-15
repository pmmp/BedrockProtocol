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
use pocketmine\network\mcpe\protocol\types\entity\EntityLink;

class SetActorLinkPacket extends DataPacket implements ClientboundPacket, ServerboundPacket{
	public const NETWORK_ID = ProtocolInfo::SET_ACTOR_LINK_PACKET;

	public EntityLink $link;

	/**
	 * @generate-create-func
	 */
	public static function create(EntityLink $link) : self{
		$result = new self;
		$result->link = $link;
		return $result;
	}

	protected function decodePayload(PacketSerializer $in) : void{
		$this->link = $in->getEntityLink();
	}

	protected function encodePayload(PacketSerializer $out) : void{
		$out->putEntityLink($this->link);
	}

	public function handle(PacketHandlerInterface $handler) : bool{
		return $handler->handleSetActorLink($this);
	}
}
