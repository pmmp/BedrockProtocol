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

use pocketmine\network\mcpe\protocol\serializer\PacketSerializer;
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

	public static function read(PacketSerializer $in) : self{
		$entities = [];
		for($i = 0, $len = $in->getUnsignedVarInt(); $i < $len; ++$i){
			$entities[] = CameraAimAssistCategoryEntityPriority::read($in);
		}

		$blocks = [];
		for($i = 0, $len = $in->getUnsignedVarInt(); $i < $len; ++$i){
			$blocks[] = CameraAimAssistCategoryBlockPriority::read($in);
		}

		$defaultEntityPriority = $in->readOptional(fn() => $in->getLInt());
		$defaultBlockPriority = $in->readOptional(fn() => $in->getLInt());
		return new self(
			$entities,
			$blocks,
			$defaultEntityPriority,
			$defaultBlockPriority
		);
	}

	public function write(PacketSerializer $out) : void{
		$out->putUnsignedVarInt(count($this->entities));
		foreach($this->entities as $entity){
			$entity->write($out);
		}

		$out->putUnsignedVarInt(count($this->blocks));
		foreach($this->blocks as $block){
			$block->write($out);
		}

		$out->writeOptional($this->defaultEntityPriority, fn(int $v) => $out->putLInt($v));
		$out->writeOptional($this->defaultBlockPriority, fn(int $v) => $out->putLInt($v));
	}
}
