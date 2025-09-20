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
use pmmp\encoding\LE;
use pmmp\encoding\VarInt;
use pocketmine\network\mcpe\protocol\serializer\CommonTypes;
use function count;

final class CameraAimAssistCategoryPriorities{

	/**
	 * @param CameraAimAssistCategoryEntityPriority[] $entities
	 * @param CameraAimAssistCategoryBlockPriority[] $blocks
	 */
	public function __construct(
		private array $entities,
		private array $blocks,
		private ?int $defaultEntityPriority,
		private ?int $defaultBlockPriority
	){}

	/**
	 * @return CameraAimAssistCategoryEntityPriority[]
	 */
	public function getEntities() : array{ return $this->entities; }

	/**
	 * @return CameraAimAssistCategoryBlockPriority[]
	 */
	public function getBlocks() : array{ return $this->blocks; }

	public function getDefaultEntityPriority() : ?int{ return $this->defaultEntityPriority; }

	public function getDefaultBlockPriority() : ?int{ return $this->defaultBlockPriority; }

	public static function read(ByteBufferReader $in) : self{
		$entities = [];
		for($i = 0, $len = VarInt::readUnsignedInt($in); $i < $len; ++$i){
			$entities[] = CameraAimAssistCategoryEntityPriority::read($in);
		}

		$blocks = [];
		for($i = 0, $len = VarInt::readUnsignedInt($in); $i < $len; ++$i){
			$blocks[] = CameraAimAssistCategoryBlockPriority::read($in);
		}

		$defaultEntityPriority = CommonTypes::readOptional($in, LE::readSignedInt(...));
		$defaultBlockPriority = CommonTypes::readOptional($in, LE::readSignedInt(...));
		return new self(
			$entities,
			$blocks,
			$defaultEntityPriority,
			$defaultBlockPriority
		);
	}

	public function write(ByteBufferWriter $out) : void{
		VarInt::writeUnsignedInt($out, count($this->entities));
		foreach($this->entities as $entity){
			$entity->write($out);
		}

		VarInt::writeUnsignedInt($out, count($this->blocks));
		foreach($this->blocks as $block){
			$block->write($out);
		}

		CommonTypes::writeOptional($out, $this->defaultEntityPriority, LE::writeSignedInt(...));
		CommonTypes::writeOptional($out, $this->defaultBlockPriority, LE::writeSignedInt(...));
	}
}
