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
use pocketmine\network\mcpe\protocol\types\BoolPackSetting;
use pocketmine\network\mcpe\protocol\types\FloatPackSetting;
use pocketmine\network\mcpe\protocol\types\PackSetting;
use pocketmine\network\mcpe\protocol\types\PackSettingType;
use pocketmine\network\mcpe\protocol\types\StringPackSetting;
use Ramsey\Uuid\UuidInterface;

class ServerboundPackSettingChangePacket extends DataPacket implements ServerboundPacket{
	public const NETWORK_ID = ProtocolInfo::SERVERBOUND_PACK_SETTING_CHANGE_PACKET;

	private UuidInterface $packId;
	private PackSetting $packSetting;

	/**
	 * @generate-create-func
	 */
	public static function create(UuidInterface $packId, PackSetting $packSetting) : self{
		$result = new self;
		$result->packId = $packId;
		$result->packSetting = $packSetting;
		return $result;
	}

	public function getPackId() : UuidInterface{ return $this->packId; }

	public function getPackSetting() : PackSetting{ return $this->packSetting; }

	protected function decodePayload(ByteBufferReader $in) : void{
		$this->packId = CommonTypes::getUUID($in);

		$name = CommonTypes::getString($in);
		$typeId = PackSettingType::from(VarInt::readUnsignedInt($in));
		$this->packSetting = match($typeId){
			PackSettingType::FLOAT => FloatPackSetting::read($in, $name),
			PackSettingType::BOOL => BoolPackSetting::read($in, $name),
			PackSettingType::STRING => StringPackSetting::read($in, $name),
		};
	}

	protected function encodePayload(ByteBufferWriter $out) : void{
		CommonTypes::putUUID($out, $this->packId);
		CommonTypes::putString($out, $this->packSetting->getName());
		VarInt::writeUnsignedInt($out, $this->packSetting->getTypeId()->value);
		$this->packSetting->write($out);
	}

	public function handle(PacketHandlerInterface $handler) : bool{
		return $handler->handleServerboundPackSettingChange($this);
	}
}
