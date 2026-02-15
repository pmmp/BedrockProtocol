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
	 * @param CameraAimAssistCategoryPriority[] $entities
	 * @param CameraAimAssistCategoryPriority[] $blocks
	 * @param CameraAimAssistCategoryPriority[] $blockTags
	 * @param CameraAimAssistCategoryPriority[] $entityTypeFamilies
	 */
	public function __construct(
		private array $entities,
		private array $blocks,
		private array $blockTags,
		private array $entityTypeFamilies,
		private ?int $defaultEntityPriority,
		private ?int $defaultBlockPriority
	){}

	/**
	 * @return CameraAimAssistCategoryPriority[]
	 */
	public function getEntities() : array{ return $this->entities; }

	/**
	 * @return CameraAimAssistCategoryPriority[]
	 */
	public function getBlocks() : array{ return $this->blocks; }

	/**
	 * @return CameraAimAssistCategoryPriority[]
	 */
	public function getBlockTags() : array{ return $this->blockTags; }

	/**
	 * @return CameraAimAssistCategoryPriority[]
	 */
	public function getEntityTypeFamilies() : array{ return $this->entityTypeFamilies; }

	public function getDefaultEntityPriority() : ?int{ return $this->defaultEntityPriority; }

	public function getDefaultBlockPriority() : ?int{ return $this->defaultBlockPriority; }

	public static function read(ByteBufferReader $in) : self{
		$entities = [];
		for($i = 0, $len = VarInt::readUnsignedInt($in); $i < $len; ++$i){
			$entities[] = CameraAimAssistCategoryPriority::read($in);
		}

		$blocks = [];
		for($i = 0, $len = VarInt::readUnsignedInt($in); $i < $len; ++$i){
			$blocks[] = CameraAimAssistCategoryPriority::read($in);
		}

		$blockTags = [];
		for($i = 0, $len = VarInt::readUnsignedInt($in); $i < $len; ++$i){
			$blockTags[] = CameraAimAssistCategoryPriority::read($in);
		}

		$entityTypeFamilies = [];
		for($i = 0, $len = VarInt::readUnsignedInt($in); $i < $len; ++$i){
			$entityTypeFamilies[] = CameraAimAssistCategoryPriority::read($in);
		}

		$defaultEntityPriority = CommonTypes::readOptional($in, LE::readSignedInt(...));
		$defaultBlockPriority = CommonTypes::readOptional($in, LE::readSignedInt(...));
		return new self(
			$entities,
			$blocks,
			$blockTags,
			$entityTypeFamilies,
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

		VarInt::writeUnsignedInt($out, count($this->blockTags));
		foreach($this->blockTags as $tag){
			$tag->write($out);
		}

		VarInt::writeUnsignedInt($out, count($this->entityTypeFamilies));
		foreach($this->entityTypeFamilies as $family){
			$family->write($out);
		}

		CommonTypes::writeOptional($out, $this->defaultEntityPriority, LE::writeSignedInt(...));
		CommonTypes::writeOptional($out, $this->defaultBlockPriority, LE::writeSignedInt(...));
	}
}
