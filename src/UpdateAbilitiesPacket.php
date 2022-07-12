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
use pocketmine\network\mcpe\protocol\types\command\CommandPermissions;
use pocketmine\network\mcpe\protocol\types\PlayerPermissions;
use pocketmine\network\mcpe\protocol\types\UpdateAbilitiesPacketLayer;
use function count;

/**
 * Updates player abilities and permissions, such as command permissions, flying/noclip, fly speed, walk speed etc.
 * Abilities may be layered in order to combine different ability sets into a resulting set.
 */
class UpdateAbilitiesPacket extends DataPacket implements ClientboundPacket{
	public const NETWORK_ID = ProtocolInfo::UPDATE_ABILITIES_PACKET;

	private int $commandPermission = CommandPermissions::NORMAL;
	private int $playerPermission = PlayerPermissions::MEMBER;
	private int $targetActorUniqueId; //This is a little-endian long, NOT a var-long. (WTF Mojang)
	/**
	 * @var UpdateAbilitiesPacketLayer[]
	 * @phpstan-var array<int, UpdateAbilitiesPacketLayer>
	 */
	private array $abilityLayers;

	/**
	 * @generate-create-func
	 * @param UpdateAbilitiesPacketLayer[] $abilityLayers
	 * @phpstan-param array<int, UpdateAbilitiesPacketLayer> $abilityLayers
	 */
	public static function create(int $commandPermission, int $playerPermission, int $targetActorUniqueId, array $abilityLayers) : self{
		$result = new self;
		$result->commandPermission = $commandPermission;
		$result->playerPermission = $playerPermission;
		$result->targetActorUniqueId = $targetActorUniqueId;
		$result->abilityLayers = $abilityLayers;
		return $result;
	}

	public function getCommandPermission() : int{ return $this->commandPermission; }

	public function getPlayerPermission() : int{ return $this->playerPermission; }

	public function getTargetActorUniqueId() : int{ return $this->targetActorUniqueId; }

	/** @return UpdateAbilitiesPacketLayer[] */
	public function getAbilityLayers() : array{ return $this->abilityLayers; }

	protected function decodePayload(PacketSerializer $in) : void{
		$this->targetActorUniqueId = $in->getLLong(); //WHY IS THIS NON-STANDARD?
		$this->playerPermission = $in->getByte();
		$this->commandPermission = $in->getByte();

		$this->abilityLayers = [];
		for($i = 0, $len = $in->getByte(); $i < $len; $i++){
			$this->abilityLayers[] = UpdateAbilitiesPacketLayer::decode($in);
		}
	}

	protected function encodePayload(PacketSerializer $out) : void{
		$out->putLLong($this->targetActorUniqueId);
		$out->putByte($this->playerPermission);
		$out->putByte($this->commandPermission);

		$out->putByte(count($this->abilityLayers));
		foreach($this->abilityLayers as $layer){
			$layer->encode($out);
		}
	}

	public function handle(PacketHandlerInterface $handler) : bool{
		return $handler->handleUpdateAbilities($this);
	}
}
