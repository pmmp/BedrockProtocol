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
 * Updates "adventure settings". In vanilla, these flags apply to the whole world. This differs from abilities, which
 * apply only to the local player itself.
 * In practice, there's no difference between the two for a custom server.
 * This includes flags such as worldImmutable (makes players unable to build), autoJump, showNameTags, noPvM, and noMvP.
 */
class UpdateAdventureSettingsPacket extends DataPacket implements ClientboundPacket{
	public const NETWORK_ID = ProtocolInfo::UPDATE_ADVENTURE_SETTINGS_PACKET;

	private bool $noAttackingMobs;
	private bool $noAttackingPlayers;
	private bool $worldImmutable;
	private bool $showNameTags;
	private bool $autoJump;

	/**
	 * @generate-create-func
	 */
	public static function create(bool $noAttackingMobs, bool $noAttackingPlayers, bool $worldImmutable, bool $showNameTags, bool $autoJump) : self{
		$result = new self;
		$result->noAttackingMobs = $noAttackingMobs;
		$result->noAttackingPlayers = $noAttackingPlayers;
		$result->worldImmutable = $worldImmutable;
		$result->showNameTags = $showNameTags;
		$result->autoJump = $autoJump;
		return $result;
	}

	public function isNoAttackingMobs() : bool{ return $this->noAttackingMobs; }

	public function isNoAttackingPlayers() : bool{ return $this->noAttackingPlayers; }

	public function isWorldImmutable() : bool{ return $this->worldImmutable; }

	public function isShowNameTags() : bool{ return $this->showNameTags; }

	public function isAutoJump() : bool{ return $this->autoJump; }

	protected function decodePayload(PacketSerializer $in) : void{
		$this->noAttackingMobs = $in->getBool();
		$this->noAttackingPlayers = $in->getBool();
		$this->worldImmutable = $in->getBool();
		$this->showNameTags = $in->getBool();
		$this->autoJump = $in->getBool();
	}

	protected function encodePayload(PacketSerializer $out) : void{
		$out->putBool($this->noAttackingMobs);
		$out->putBool($this->noAttackingPlayers);
		$out->putBool($this->worldImmutable);
		$out->putBool($this->showNameTags);
		$out->putBool($this->autoJump);
	}

	public function handle(PacketHandlerInterface $handler) : bool{
		return $handler->handleUpdateAdventureSettings($this);
	}
}
