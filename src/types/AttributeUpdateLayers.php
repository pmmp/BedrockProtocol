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
use pmmp\encoding\VarInt;
use function count;

/**
 * @see ClientboundAttributeLayerSyncPacket
 */
final class AttributeUpdateLayers extends AttributeLayerSyncPayload{
	public const ID = AttributeLayerSyncType::UPDATE_LAYERS;

	/**
	 * @param AttributeLayer[] $layers
	 * @phpstan-param list<AttributeLayer> $layers
	 */
	public function __construct(
		private array $layers,
	){}

	public function getTypeId() : int{
		return self::ID;
	}

	/**
	 * @return AttributeLayer[]
	 * @phpstan-return list<AttributeLayer>
	 */
	public function getLayers() : array{ return $this->layers; }

	public static function read(ByteBufferReader $in) : self{
		$layers = [];
		for($i = 0, $len = VarInt::readUnsignedInt($in); $i < $len; ++$i){
			$layers[] = AttributeLayer::read($in);
		}

		return new self(
			$layers,
		);
	}

	public function write(ByteBufferWriter $out) : void{
		VarInt::writeUnsignedInt($out, count($this->layers));
		foreach($this->layers as $layer){
			$layer->write($out);
		}
	}
}
