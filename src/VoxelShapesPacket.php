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

use pmmp\encoding\ByteBufferReader;
use pmmp\encoding\ByteBufferWriter;
use pmmp\encoding\LE;
use pmmp\encoding\VarInt;
use pocketmine\network\mcpe\protocol\serializer\CommonTypes;
use pocketmine\network\mcpe\protocol\types\SerializableVoxelShape;
use function count;

class VoxelShapesPacket extends DataPacket implements ClientboundPacket{
	public const NETWORK_ID = ProtocolInfo::VOXEL_SHAPES_PACKET;

	/**
	 * @var SerializableVoxelShape[]
	 * @phpstan-var list<SerializableVoxelShape>
	 */
	private array $shapes;
	/**
	 * @var int[]
	 * @phpstan-var array<string, int>
	 */
	private array $nameMap;

	/**
	 * @generate-create-func
	 * @param SerializableVoxelShape[] $shapes
	 * @param int[]                    $nameMap
	 * @phpstan-param list<SerializableVoxelShape> $shapes
	 * @phpstan-param array<string, int>           $nameMap
	 */
	public static function create(array $shapes, array $nameMap) : self{
		$result = new self;
		$result->shapes = $shapes;
		$result->nameMap = $nameMap;
		return $result;
	}

	/**
	 * @return SerializableVoxelShape[]
	 * @phpstan-return list<SerializableVoxelShape>
	 */
	public function getShapes() : array{ return $this->shapes; }

	/**
	 * @return int[]
	 * @phpstan-return array<string, int>
	 */
	public function getNameMap() : array{ return $this->nameMap; }

	protected function decodePayload(ByteBufferReader $in) : void{
		$this->shapes = [];
		for($i = 0, $shapesCount = VarInt::readUnsignedInt($in); $i < $shapesCount; ++$i){
			$this->shapes[] = SerializableVoxelShape::read($in);
		}

		$this->nameMap = [];
		for($i = 0, $namesCount = VarInt::readUnsignedInt($in); $i < $namesCount; ++$i){
			$name = CommonTypes::getString($in);
			$id = LE::readUnsignedShort($in);
			$this->nameMap[$name] = $id;
		}
	}

	protected function encodePayload(ByteBufferWriter $out) : void{
		VarInt::writeUnsignedInt($out, count($this->shapes));
		foreach($this->shapes as $shape){
			$shape->write($out);
		}

		VarInt::writeUnsignedInt($out, count($this->nameMap));
		foreach($this->nameMap as $name => $id){
			CommonTypes::putString($out, $name);
			LE::writeUnsignedShort($out, $id);
		}
	}

	public function handle(PacketHandlerInterface $handler) : bool{
		return $handler->handleVoxelShapes($this);
	}
}
