<?php

/*
 *
 *  ____            _        _   __  __ _                  __  __ ____
 * |  _ \ ___   ___| | _____| |_|  \/  (_)_ __   ___      |  \/  |  _ \
 * | |_) / _ \ / __| |/ / _ \ __| |\/| | | '_ \ / _ \_____| |\/| | |_) |
 * |  __/ (_) | (__|   <  __/ |_| |  | | | | | |  __/_____| |  | |  __/
 * |_|   \___/ \___|_|\_\___|\__|_|  |_|_|_| |_|\___|     |_|  |_|_|
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * @author PocketMine Team
 * @link http://www.pocketmine.net/
 *
 *
*/

declare(strict_types=1);

namespace pocketmine\network\mcpe\protocol;

#include <rules/DataPacket.h>

use pocketmine\math\Vector3;
use pocketmine\network\mcpe\protocol\serializer\PacketSerializer;
use pocketmine\network\mcpe\protocol\types\entity\MetadataProperty;
use pocketmine\network\mcpe\protocol\types\inventory\ItemStackWrapper;

class AddItemActorPacket extends DataPacket implements ClientboundPacket{
	public const NETWORK_ID = ProtocolInfo::ADD_ITEM_ACTOR_PACKET;

	public ?int $actorUniqueId = null; //TODO
	public int $actorRuntimeId;
	public ItemStackWrapper $item;
	public Vector3 $position;
	public ?Vector3 $motion = null;
	/**
	 * @var MetadataProperty[]
	 * @phpstan-var array<int, MetadataProperty>
	 */
	public array $metadata = [];
	public bool $isFromFishing = false;

	protected function decodePayload(PacketSerializer $in) : void{
		$this->actorUniqueId = $in->getActorUniqueId();
		$this->actorRuntimeId = $in->getActorRuntimeId();
		$this->item = ItemStackWrapper::read($in);
		$this->position = $in->getVector3();
		$this->motion = $in->getVector3();
		$this->metadata = $in->getEntityMetadata();
		$this->isFromFishing = $in->getBool();
	}

	protected function encodePayload(PacketSerializer $out) : void{
		$out->putActorUniqueId($this->actorUniqueId ?? $this->actorRuntimeId);
		$out->putActorRuntimeId($this->actorRuntimeId);
		$this->item->write($out);
		$out->putVector3($this->position);
		$out->putVector3Nullable($this->motion);
		$out->putEntityMetadata($this->metadata);
		$out->putBool($this->isFromFishing);
	}

	public function handle(PacketHandlerInterface $handler) : bool{
		return $handler->handleAddItemActor($this);
	}
}
