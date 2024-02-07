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
use pocketmine\network\mcpe\protocol\types\hud\HudElement;
use pocketmine\network\mcpe\protocol\types\hud\HudVisibility;
use function count;

class SetHudPacket extends DataPacket implements ClientboundPacket{
	public const NETWORK_ID = ProtocolInfo::SET_HUD_PACKET;

	/** @var HudElement[] */
	private array $hudElements = [];
	private HudVisibility $visibility;

	/**
	 * @generate-create-func
	 * @param HudElement[] $hudElements
	 */
	public static function create(array $hudElements, HudVisibility $visibility) : self{
		$result = new self;
		$result->hudElements = $hudElements;
		$result->visibility = $visibility;
		return $result;
	}

	/** @return HudElement[] */
	public function getHudElements() : array{ return $this->hudElements; }

	public function getVisibility() : HudVisibility{ return $this->visibility; }

	protected function decodePayload(PacketSerializer $in) : void{
		$this->hudElements = [];
		for($i = 0, $count = $in->getUnsignedVarInt(); $i < $count; ++$i){
			$this->hudElements[] = HudElement::fromPacket($in->getByte());
		}
		$this->visibility = HudVisibility::fromPacket($in->getByte());
	}

	protected function encodePayload(PacketSerializer $out) : void{
		$out->putUnsignedVarInt(count($this->hudElements));
		foreach($this->hudElements as $element){
			$out->putByte($element->value);
		}
		$out->putByte($this->visibility->value);
	}

	public function handle(PacketHandlerInterface $handler) : bool{
		return $handler->handleSetHud($this);
	}
}
