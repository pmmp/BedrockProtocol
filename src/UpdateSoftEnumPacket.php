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
use function count;

class UpdateSoftEnumPacket extends DataPacket implements ClientboundPacket{
	public const NETWORK_ID = ProtocolInfo::UPDATE_SOFT_ENUM_PACKET;

	public const TYPE_ADD = 0;
	public const TYPE_REMOVE = 1;
	public const TYPE_SET = 2;

	public string $enumName;
	/** @var string[] */
	public array $values = [];
	public int $type;

	/**
	 * @generate-create-func
	 * @param string[] $values
	 */
	public static function create(string $enumName, array $values, int $type) : self{
		$result = new self;
		$result->enumName = $enumName;
		$result->values = $values;
		$result->type = $type;
		return $result;
	}

	protected function decodePayload(PacketSerializer $in) : void{
		$this->enumName = $in->getString();
		for($i = 0, $count = $in->getUnsignedVarInt(); $i < $count; ++$i){
			$this->values[] = $in->getString();
		}
		$this->type = $in->getByte();
	}

	protected function encodePayload(PacketSerializer $out) : void{
		$out->putString($this->enumName);
		$out->putUnsignedVarInt(count($this->values));
		foreach($this->values as $v){
			$out->putString($v);
		}
		$out->putByte($this->type);
	}

	public function handle(PacketHandlerInterface $handler) : bool{
		return $handler->handleUpdateSoftEnum($this);
	}
}
