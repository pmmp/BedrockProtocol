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

namespace pocketmine\network\mcpe\protocol\types\camera;

use pmmp\encoding\ByteBufferReader;
use pmmp\encoding\ByteBufferWriter;
use pmmp\encoding\VarInt;
use pocketmine\network\mcpe\protocol\serializer\CommonTypes;
use function count;

final class CameraAimAssistPresetExclusionDefinition{

	/**
	 * @param string[] $blocks
	 * @param string[] $entities
	 * @param string[] $blockTags
	 */
	public function __construct(
		private array $blocks,
		private array $entities,
		private array $blockTags,
	){}

	/**
	 * @return string[]
	 */
	public function getBlocks() : array{ return $this->blocks; }

	/**
	 * @return string[]
	 */
	public function getEntities() : array{ return $this->entities; }

	/**
	 * @return string[]
	 */
	public function getBlockTags() : array{ return $this->blockTags; }

	public static function read(ByteBufferReader $in) : self{
		$blocks = [];
		for($i = 0, $len = VarInt::readUnsignedInt($in); $i < $len; ++$i){
			$blocks[] = CommonTypes::getString($in);
		}

		$entities = [];
		for($i = 0, $len = VarInt::readUnsignedInt($in); $i < $len; ++$i){
			$entities[] = CommonTypes::getString($in);
		}

		$blockTags = [];
		for($i = 0, $len = VarInt::readUnsignedInt($in); $i < $len; ++$i){
			$blockTags[] = CommonTypes::getString($in);
		}

		return new self(
			$blocks,
			$entities,
			$blockTags
		);
	}

	public function write(ByteBufferWriter $out) : void{
		VarInt::writeUnsignedInt($out, count($this->blocks));
		foreach($this->blocks as $block){
			CommonTypes::putString($out, $block);
		}

		VarInt::writeUnsignedInt($out, count($this->entities));
		foreach($this->entities as $entity){
			CommonTypes::putString($out, $entity);
		}

		VarInt::writeUnsignedInt($out, count($this->blockTags));
		foreach($this->blockTags as $blockTag){
			CommonTypes::putString($out, $blockTag);
		}
	}
}
