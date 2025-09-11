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
use pocketmine\network\mcpe\protocol\types\entity\UpdateAttribute;
use function count;

class UpdateAttributesPacket extends DataPacket implements ClientboundPacket{
	public const NETWORK_ID = ProtocolInfo::UPDATE_ATTRIBUTES_PACKET;

	public int $actorRuntimeId;
	/** @var UpdateAttribute[] */
	public array $entries = [];
	public int $tick = 0;

	/**
	 * @generate-create-func
	 * @param UpdateAttribute[] $entries
	 */
	public static function create(int $actorRuntimeId, array $entries, int $tick) : self{
		$result = new self;
		$result->actorRuntimeId = $actorRuntimeId;
		$result->entries = $entries;
		$result->tick = $tick;
		return $result;
	}

	protected function decodePayload(ByteBufferReader $in) : void{
		$this->actorRuntimeId = CommonTypes::getActorRuntimeId($in);
		for($i = 0, $len = VarInt::readUnsignedInt($in); $i < $len; ++$i){
			$this->entries[] = UpdateAttribute::read($in);
		}
		$this->tick = VarInt::readUnsignedLong($in);
	}

	protected function encodePayload(ByteBufferWriter $out) : void{
		CommonTypes::putActorRuntimeId($out, $this->actorRuntimeId);
		VarInt::writeUnsignedInt($out, count($this->entries));
		foreach($this->entries as $entry){
			$entry->write($out);
		}
		VarInt::writeUnsignedLong($out, $this->tick);
	}

	public function handle(PacketHandlerInterface $handler) : bool{
		return $handler->handleUpdateAttributes($this);
	}
}
