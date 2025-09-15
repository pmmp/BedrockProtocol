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
use pmmp\encoding\VarInt;
use pocketmine\network\mcpe\protocol\serializer\CommonTypes;
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

	protected function decodePayload(ByteBufferReader $in) : void{
		$this->enumName = CommonTypes::getString($in);
		for($i = 0, $count = VarInt::readUnsignedInt($in); $i < $count; ++$i){
			$this->values[] = CommonTypes::getString($in);
		}
		$this->type = Byte::readUnsigned($in);
	}

	protected function encodePayload(ByteBufferWriter $out) : void{
		CommonTypes::putString($out, $this->enumName);
		VarInt::writeUnsignedInt($out, count($this->values));
		foreach($this->values as $v){
			CommonTypes::putString($out, $v);
		}
		Byte::writeUnsigned($out, $this->type);
	}

	public function handle(PacketHandlerInterface $handler) : bool{
		return $handler->handleUpdateSoftEnum($this);
	}
}
