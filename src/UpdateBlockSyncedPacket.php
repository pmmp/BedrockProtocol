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

class UpdateBlockSyncedPacket extends UpdateBlockPacket{
	public const NETWORK_ID = ProtocolInfo::UPDATE_BLOCK_SYNCED_PACKET;

	public const TYPE_NONE = 0;
	public const TYPE_CREATE = 1;
	public const TYPE_DESTROY = 2;

	public int $actorUniqueId;
	public int $updateType;

	protected function decodePayload(PacketSerializer $in) : void{
		parent::decodePayload($in);
		$this->actorUniqueId = $in->getUnsignedVarLong();
		$this->updateType = $in->getUnsignedVarLong();
	}

	protected function encodePayload(PacketSerializer $out) : void{
		parent::encodePayload($out);
		$out->putUnsignedVarLong($this->actorUniqueId);
		$out->putUnsignedVarLong($this->updateType);
	}

	public function handle(PacketHandlerInterface $handler) : bool{
		return $handler->handleUpdateBlockSynced($this);
	}
}
