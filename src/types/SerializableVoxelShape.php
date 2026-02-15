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
use function count;

final class SerializableVoxelShape{

	/**
	 * @param list<SerializableVoxelCells> $cells
	 * @param list<float> $xCoordinates
	 * @param list<float> $yCoordinates
	 * @param list<float> $zCoordinates
	 */
	public function __construct(
		private array $cells,
		private array $xCoordinates,
		private array $yCoordinates,
		private array $zCoordinates
	){}

	/**
	 * @return list<SerializableVoxelCells>
	 */
	public function getCells() : array{ return $this->cells; }

	/**
	 * @return list<float>
	 */
	public function getXCoordinates() : array{ return $this->xCoordinates; }

	/**
	 * @return list<float>
	 */
	public function getYCoordinates() : array{ return $this->yCoordinates; }

	/**
	 * @return list<float>
	 */
	public function getZCoordinates() : array{ return $this->zCoordinates; }

	public static function read(ByteBufferReader $in) : self{
		$cells = [];
		for($i = 0, $cellsCount = VarInt::readUnsignedInt($in); $i < $cellsCount; ++$i){
			$cells[] = SerializableVoxelCells::read($in);
		}

		$xCoordinates = [];
		for($i = 0, $xCoordinatesCount = VarInt::readUnsignedInt($in); $i < $xCoordinatesCount; ++$i){
			$xCoordinates[] = LE::readFloat($in);
		}

		$yCoordinates = [];
		for($i = 0, $yCoordinatesCount = VarInt::readUnsignedInt($in); $i < $yCoordinatesCount; ++$i){
			$yCoordinates[] = LE::readFloat($in);
		}

		$zCoordinates = [];
		for($i = 0, $zCoordinatesCount = VarInt::readUnsignedInt($in); $i < $zCoordinatesCount; ++$i){
			$zCoordinates[] = LE::readFloat($in);
		}

		return new self(
			$cells,
			$xCoordinates,
			$yCoordinates,
			$zCoordinates
		);
	}

	public function write(ByteBufferWriter $out) : void{
		VarInt::writeUnsignedInt($out, count($this->cells));
		foreach($this->cells as $cell){
			$cell->write($out);
		}

		VarInt::writeUnsignedInt($out, count($this->xCoordinates));
		foreach($this->xCoordinates as $value){
			LE::writeFloat($out, $value);
		}

		VarInt::writeUnsignedInt($out, count($this->yCoordinates));
		foreach($this->yCoordinates as $value){
			LE::writeFloat($out, $value);
		}

		VarInt::writeUnsignedInt($out, count($this->zCoordinates));
		foreach($this->zCoordinates as $value){
			LE::writeFloat($out, $value);
		}
	}
}
