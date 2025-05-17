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
use pocketmine\network\mcpe\protocol\types\OverrideUpdateType;

class PlayerUpdateEntityOverridesPacket extends DataPacket implements ClientboundPacket{
	public const NETWORK_ID = ProtocolInfo::PLAYER_UPDATE_ENTITY_OVERRIDES_PACKET;

	private int $actorRuntimeId;
	private int $propertyIndex;
	private OverrideUpdateType $updateType;
	private ?int $intOverrideValue;
	private ?float $floatOverrideValue;

	/**
	 * @generate-create-func
	 */
	private static function create(int $actorRuntimeId, int $propertyIndex, OverrideUpdateType $updateType, ?int $intOverrideValue, ?float $floatOverrideValue) : self{
		$result = new self;
		$result->actorRuntimeId = $actorRuntimeId;
		$result->propertyIndex = $propertyIndex;
		$result->updateType = $updateType;
		$result->intOverrideValue = $intOverrideValue;
		$result->floatOverrideValue = $floatOverrideValue;
		return $result;
	}

	public static function createIntOverride(int $actorRuntimeId, int $propertyIndex, int $value) : self{
		return self::create($actorRuntimeId, $propertyIndex, OverrideUpdateType::SET_INT_OVERRIDE, $value, null);
	}

	public static function createFloatOverride(int $actorRuntimeId, int $propertyIndex, float $value) : self{
		return self::create($actorRuntimeId, $propertyIndex, OverrideUpdateType::SET_FLOAT_OVERRIDE, null, $value);
	}

	public static function createClearOverrides(int $actorRuntimeId, int $propertyIndex) : self{
		return self::create($actorRuntimeId, $propertyIndex, OverrideUpdateType::CLEAR_OVERRIDES, null, null);
	}

	public static function createRemoveOverride(int $actorRuntimeId, int $propertyIndex) : self{
		return self::create($actorRuntimeId, $propertyIndex, OverrideUpdateType::REMOVE_OVERRIDE, null, null);
	}

	public function getActorRuntimeId() : int{ return $this->actorRuntimeId; }

	public function getPropertyIndex() : int{ return $this->propertyIndex; }

	public function getUpdateType() : OverrideUpdateType{ return $this->updateType; }

	public function getIntOverrideValue() : ?int{ return $this->intOverrideValue; }

	public function getFloatOverrideValue() : ?float{ return $this->floatOverrideValue; }

	protected function decodePayload(PacketSerializer $in) : void{
		$this->actorRuntimeId = $in->getActorRuntimeId();
		$this->propertyIndex = $in->getUnsignedVarInt();
		$this->updateType = OverrideUpdateType::fromPacket($in->getByte());
		if($this->updateType === OverrideUpdateType::SET_INT_OVERRIDE){
			$this->intOverrideValue = $in->getLInt();
		}elseif($this->updateType === OverrideUpdateType::SET_FLOAT_OVERRIDE){
			$this->floatOverrideValue = $in->getLFloat();
		}
	}

	protected function encodePayload(PacketSerializer $out) : void{
		$out->putActorRuntimeId($this->actorRuntimeId);
		$out->putUnsignedVarInt($this->propertyIndex);
		$out->putByte($this->updateType->value);
		if($this->updateType === OverrideUpdateType::SET_INT_OVERRIDE){
			if($this->intOverrideValue === null){ // this should never be the case
				throw new \LogicException("PlayerUpdateEntityOverridesPacket with type SET_INT_OVERRIDE requires intOverrideValue to be provided");
			}
			$out->putLInt($this->intOverrideValue);
		}elseif($this->updateType === OverrideUpdateType::SET_FLOAT_OVERRIDE){
			if($this->floatOverrideValue === null){ // this should never be the case
				throw new \LogicException("PlayerUpdateEntityOverridesPacket with type SET_FLOAT_OVERRIDE requires floatOverrideValue to be provided");
			}
			$out->putLFloat($this->floatOverrideValue);
		}
	}

	public function handle(PacketHandlerInterface $handler) : bool{
		return $handler->handlePlayerUpdateEntityOverrides($this);
	}
}
