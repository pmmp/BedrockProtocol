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
use pocketmine\network\mcpe\protocol\types\EnchantOption;
use function count;

class PlayerEnchantOptionsPacket extends DataPacket implements ClientboundPacket{
	public const NETWORK_ID = ProtocolInfo::PLAYER_ENCHANT_OPTIONS_PACKET;

	/** @var EnchantOption[] */
	private array $options;

	/**
	 * @generate-create-func
	 * @param EnchantOption[] $options
	 */
	public static function create(array $options) : self{
		$result = new self;
		$result->options = $options;
		return $result;
	}

	/**
	 * @return EnchantOption[]
	 */
	public function getOptions() : array{ return $this->options; }

	protected function decodePayload(PacketSerializer $in) : void{
		$this->options = [];
		for($i = 0, $len = $in->getUnsignedVarInt(); $i < $len; ++$i){
			$this->options[] = EnchantOption::read($in);
		}
	}

	protected function encodePayload(PacketSerializer $out) : void{
		$out->putUnsignedVarInt(count($this->options));
		foreach($this->options as $option){
			$option->write($out);
		}
	}

	public function handle(PacketHandlerInterface $handler) : bool{
		return $handler->handlePlayerEnchantOptions($this);
	}
}
