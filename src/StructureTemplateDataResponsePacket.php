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
use pocketmine\network\mcpe\protocol\types\CacheableNbt;

class StructureTemplateDataResponsePacket extends DataPacket implements ClientboundPacket{
	public const NETWORK_ID = ProtocolInfo::STRUCTURE_TEMPLATE_DATA_RESPONSE_PACKET;

	public const TYPE_FAILURE = 0;
	public const TYPE_EXPORT = 1;
	public const TYPE_QUERY = 2;
	public const TYPE_IMPORT = 3;

	public string $structureTemplateName;
	/** @phpstan-var CacheableNbt<\pocketmine\nbt\tag\CompoundTag> */
	public ?CacheableNbt $nbt;
	public int $responseType;

	/**
	 * @generate-create-func
	 * @phpstan-param CacheableNbt<\pocketmine\nbt\tag\CompoundTag> $nbt
	 */
	public static function create(string $structureTemplateName, ?CacheableNbt $nbt, int $responseType) : self{
		$result = new self;
		$result->structureTemplateName = $structureTemplateName;
		$result->nbt = $nbt;
		$result->responseType = $responseType;
		return $result;
	}

	protected function decodePayload(PacketSerializer $in) : void{
		$this->structureTemplateName = $in->getString();
		if($in->getBool()){
			$this->nbt = new CacheableNbt($in->getNbtCompoundRoot());
		}
		$this->responseType = $in->getByte();
	}

	protected function encodePayload(PacketSerializer $out) : void{
		$out->putString($this->structureTemplateName);
		$out->putBool($this->nbt !== null);
		if($this->nbt !== null){
			$out->put($this->nbt->getEncodedNbt());
		}
		$out->putByte($this->responseType);
	}

	public function handle(PacketHandlerInterface $handler) : bool{
		return $handler->handleStructureTemplateDataResponse($this);
	}
}
