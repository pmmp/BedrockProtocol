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

/**
 * This appears to be some kind of debug packet. Does nothing in release mode.
 * I have no words for the structure of this packet ...
 */
class ChangeMobPropertyPacket extends DataPacket implements ServerboundPacket{
	public const NETWORK_ID = ProtocolInfo::CHANGE_MOB_PROPERTY_PACKET;

	private int $actorUniqueId;
	private string $propertyName;
	private bool $boolValue;
	private string $stringValue;
	private int $intValue;
	private float $floatValue;

	/**
	 * @generate-create-func
	 */
	private static function create(int $actorUniqueId, string $propertyName, bool $boolValue, string $stringValue, int $intValue, float $floatValue) : self{
		$result = new self;
		$result->actorUniqueId = $actorUniqueId;
		$result->propertyName = $propertyName;
		$result->boolValue = $boolValue;
		$result->stringValue = $stringValue;
		$result->intValue = $intValue;
		$result->floatValue = $floatValue;
		return $result;
	}

	public static function boolValue(int $actorUniqueId, string $propertyName, bool $value) : self{
		return self::create($actorUniqueId, $propertyName, $value, "", 0, 0);
	}

	public static function stringValue(int $actorUniqueId, string $propertyName, string $value) : self{
		return self::create($actorUniqueId, $propertyName, false, $value, 0, 0);
	}

	public static function intValue(int $actorUniqueId, string $propertyName, int $value) : self{
		return self::create($actorUniqueId, $propertyName, false, "", $value, 0);
	}

	public static function floatValue(int $actorUniqueId, string $propertyName, float $value) : self{
		return self::create($actorUniqueId, $propertyName, false, "", 0, $value);
	}

	public function getActorUniqueId() : int{ return $this->actorUniqueId; }

	public function getPropertyName() : string{ return $this->propertyName; }

	public function isBoolValue() : bool{ return $this->boolValue; }

	public function getStringValue() : string{ return $this->stringValue; }

	public function getIntValue() : int{ return $this->intValue; }

	public function getFloatValue() : float{ return $this->floatValue; }

	protected function decodePayload(PacketSerializer $in) : void{
		$this->actorUniqueId = $in->getActorUniqueId();
		$this->propertyName = $in->getString();
		$this->boolValue = $in->getBool();
		$this->stringValue = $in->getString();
		$this->intValue = $in->getVarInt();
		$this->floatValue = $in->getLFloat();
	}

	protected function encodePayload(PacketSerializer $out) : void{
		$out->putActorUniqueId($this->actorUniqueId);
		$out->putString($this->propertyName);
		$out->putBool($this->boolValue);
		$out->putString($this->stringValue);
		$out->putVarInt($this->intValue);
		$out->putLFloat($this->floatValue);
	}

	public function handle(PacketHandlerInterface $handler) : bool{
		return $handler->handleChangeMobProperty($this);
	}
}
