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
use pocketmine\network\mcpe\protocol\types\ArmorSlotAndDamagePair;
use function count;

class PlayerArmorDamagePacket extends DataPacket implements ClientboundPacket{
	public const NETWORK_ID = ProtocolInfo::PLAYER_ARMOR_DAMAGE_PACKET;

	/**
	 * @var ArmorSlotAndDamagePair[]
	 * @phpstan-var list<ArmorSlotAndDamagePair>
	 */
	private array $armorSlotAndDamagePairs = [];

	/**
	 * @generate-create-func
	 * @param ArmorSlotAndDamagePair[] $armorSlotAndDamagePairs
	 * @phpstan-param list<ArmorSlotAndDamagePair> $armorSlotAndDamagePairs
	 */
	public static function create(array $armorSlotAndDamagePairs) : self{
		$result = new self;
		$result->armorSlotAndDamagePairs = $armorSlotAndDamagePairs;
		return $result;
	}

	/**
	 * @return ArmorSlotAndDamagePair[]
	 * @phpstan-return list<ArmorSlotAndDamagePair>
	 */
	public function getArmorSlotAndDamagePairs() : array{
		return $this->armorSlotAndDamagePairs;
	}

	protected function decodePayload(ByteBufferReader $in) : void{
		for($i = 0, $count = VarInt::readUnsignedInt($in); $i < $count; ++$i){
			$this->armorSlotAndDamagePairs[] = ArmorSlotAndDamagePair::read($in);
		}
	}

	protected function encodePayload(ByteBufferWriter $out) : void{
		VarInt::writeUnsignedInt($out, count($this->armorSlotAndDamagePairs));
		foreach($this->armorSlotAndDamagePairs as $pair){
			$pair->write($out);
		}
	}

	public function handle(PacketHandlerInterface $handler) : bool{
		return $handler->handlePlayerArmorDamage($this);
	}
}
