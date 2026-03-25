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

namespace pocketmine\network\mcpe\protocol\types;

use pmmp\encoding\ByteBufferReader;
use pmmp\encoding\ByteBufferWriter;
use pmmp\encoding\LE;
use pmmp\encoding\VarInt;
use pocketmine\network\mcpe\protocol\PacketDecodeException;
use pocketmine\network\mcpe\protocol\serializer\CommonTypes;

/**
 * @see AttributeLayer&AttributeUpdateLayerSettings
 */
final class AttributeLayerSettings{

	public function __construct(
		private int $priority,
		private AttributeLayerSettingsWeight $weight,
		private bool $enabled,
		private bool $transitionsPaused,
	){}

	public function getPriority() : int{ return $this->priority; }

	public function getWeight() : AttributeLayerSettingsWeight{ return $this->weight; }

	public function isEnabled() : bool{ return $this->enabled; }

	public function isTransitionsPaused() : bool{ return $this->transitionsPaused; }

	public static function read(ByteBufferReader $in) : self{
		$priority = LE::readSignedInt($in);
		$weight = match (VarInt::readUnsignedInt($in)){
			AttributeLayerSettingsWeightFloat::ID => AttributeLayerSettingsWeightFloat::read($in),
			AttributeLayerSettingsWeightString::ID => AttributeLayerSettingsWeightString::read($in),
			default => throw new PacketDecodeException("Unknown AttributeLayerSettingsWeight type"),
		};
		$enabled = CommonTypes::getBool($in);
		$transitionsPaused = CommonTypes::getBool($in);

		return new self(
			$priority,
			$weight,
			$enabled,
			$transitionsPaused,
		);
	}

	public function write(ByteBufferWriter $out) : void{
		LE::writeSignedInt($out, $this->priority);
		VarInt::writeUnsignedInt($out, $this->weight->getTypeId());
		$this->weight->write($out);
		CommonTypes::putBool($out, $this->enabled);
		CommonTypes::putBool($out, $this->transitionsPaused);
	}
}
