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

use pocketmine\network\mcpe\protocol\serializer\PacketSerializer;
use pocketmine\network\mcpe\protocol\types\CacheableNbt;

class StructureTemplateDataResponsePacket extends DataPacket implements ClientboundPacket{
	public const NETWORK_ID = ProtocolInfo::STRUCTURE_TEMPLATE_DATA_RESPONSE_PACKET;

	public string $structureTemplateName;
	/** @phpstan-var CacheableNbt<\pocketmine\nbt\tag\CompoundTag> */
	public ?CacheableNbt $nbt;

	/**
	 * @generate-create-func
	 * @phpstan-param CacheableNbt<\pocketmine\nbt\tag\CompoundTag> $nbt
	 */
	public static function create(string $structureTemplateName, ?CacheableNbt $nbt) : self{
		$result = new self;
		$result->structureTemplateName = $structureTemplateName;
		$result->nbt = $nbt;
		return $result;
	}

	protected function decodePayload(PacketSerializer $in) : void{
		$this->structureTemplateName = $in->getString();
		if($in->getBool()){
			$this->nbt = new CacheableNbt($in->getNbtCompoundRoot());
		}
	}

	protected function encodePayload(PacketSerializer $out) : void{
		$out->putString($this->structureTemplateName);
		$out->putBool($this->nbt !== null);
		if($this->nbt !== null){
			$out->put($this->nbt->getEncodedNbt());
		}
	}

	public function handle(PacketHandlerInterface $handler) : bool{
		return $handler->handleStructureTemplateDataResponse($this);
	}
}
