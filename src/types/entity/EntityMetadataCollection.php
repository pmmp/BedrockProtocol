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

namespace pocketmine\network\mcpe\protocol\types\entity;

use pocketmine\math\Vector3;
use pocketmine\network\mcpe\protocol\types\BlockPosition;
use pocketmine\network\mcpe\protocol\types\CacheableNbt;
use function get_class;
use function get_debug_type;

class EntityMetadataCollection{

	/**
	 * @var MetadataProperty[]
	 * @phpstan-var array<int, MetadataProperty>
	 */
	private array $properties = [];
	/**
	 * @var MetadataProperty[]
	 * @phpstan-var array<int, MetadataProperty>
	 */
	private array $dirtyProperties = [];

	public function __construct(){

	}

	public function setByte(int $key, int $value, bool $force = false) : void{

		$this->set($key, new ByteMetadataProperty($value), $force);
	}

	public function setShort(int $key, int $value, bool $force = false) : void{
		$this->set($key, new ShortMetadataProperty($value), $force);
	}

	public function setInt(int $key, int $value, bool $force = false) : void{
		$this->set($key, new IntMetadataProperty($value), $force);
	}

	public function setFloat(int $key, float $value, bool $force = false) : void{
		$this->set($key, new FloatMetadataProperty($value), $force);
	}

	public function setString(int $key, string $value, bool $force = false) : void{
		$this->set($key, new StringMetadataProperty($value), $force);
	}

	/**
	 * @phpstan-param CacheableNbt<\pocketmine\nbt\tag\CompoundTag> $value
	 */
	public function setCompoundTag(int $key, CacheableNbt $value, bool $force = false) : void{
		$this->set($key, new CompoundTagMetadataProperty($value), $force);
	}

	public function setBlockPos(int $key, ?BlockPosition $value, bool $force = false) : void{
		$this->set($key, new BlockPosMetadataProperty($value ?? new BlockPosition(0, 0, 0)), $force);
	}

	public function setLong(int $key, int $value, bool $force = false) : void{
		$this->set($key, new LongMetadataProperty($value), $force);
	}

	public function setVector3(int $key, ?Vector3 $value, bool $force = false) : void{
		$this->set($key, new Vec3MetadataProperty($value ?? new Vector3(0, 0, 0)), $force);
	}

	public function set(int $key, MetadataProperty $value, bool $force = false) : void{
		if(!$force and isset($this->properties[$key]) and !($this->properties[$key] instanceof $value)){
			throw new \InvalidArgumentException("Can't overwrite property with mismatching types (have " . get_class($this->properties[$key]) . ")");
		}
		if(!isset($this->properties[$key]) or !$this->properties[$key]->equals($value)){
			$this->properties[$key] = $this->dirtyProperties[$key] = $value;
		}
	}

	/**
	 * Set a group of properties together. If any of them are changed, they will all be flagged as dirty.
	 *
	 * @param MetadataProperty[] $properties
	 * @phpstan-param array<int, MetadataProperty> $properties
	 */
	public function setAtomicBatch(array $properties, bool $force = false) : void{
		$anyDirty = false;
		if(!$force){
			foreach($properties as $key => $value){
				if(isset($this->properties[$key]) and !($this->properties[$key] instanceof $value)){
					throw new \InvalidArgumentException("Can't overwrite " . get_class($this->properties[$key]) . " with " . get_debug_type($value));
				}
			}
		}
		foreach($properties as $key => $value){
			if(!isset($this->properties[$key]) or !$this->properties[$key]->equals($value)){
				$anyDirty = true;
				break;
			}
		}
		if($anyDirty){
			foreach($properties as $key => $value){
				$this->properties[$key] = $this->dirtyProperties[$key] = $value;
			}
		}
	}

	public function setGenericFlag(int $flagId, bool $value) : void{
		$propertyId = $flagId >= 64 ? EntityMetadataProperties::FLAGS2 : EntityMetadataProperties::FLAGS;
		$realFlagId = $flagId % 64;
		$flagSetProp = $this->properties[$propertyId] ?? null;
		if($flagSetProp === null){
			$flagSet = 0;
		}elseif($flagSetProp instanceof LongMetadataProperty){
			$flagSet = $flagSetProp->getValue();
		}else{
			throw new \InvalidArgumentException("Wrong type found for flags, want long, but have " . get_class($flagSetProp));
		}

		if((($flagSet >> $realFlagId) & 1) !== ($value ? 1 : 0)){
			$flagSet ^= (1 << $realFlagId);
			$this->setLong($propertyId, $flagSet);
		}
	}

	public function setPlayerFlag(int $flagId, bool $value) : void{
		$flagSetProp = $this->properties[EntityMetadataProperties::PLAYER_FLAGS] ?? null;
		if($flagSetProp === null){
			$flagSet = 0;
		}elseif($flagSetProp instanceof ByteMetadataProperty){
			$flagSet = $flagSetProp->getValue();
		}else{
			throw new \InvalidArgumentException("Wrong type found for flags, want byte, but have " . get_class($flagSetProp));
		}
		if((($flagSet >> $flagId) & 1) !== ($value ? 1 : 0)){
			$flagSet ^= (1 << $flagId);
			$this->setByte(EntityMetadataProperties::PLAYER_FLAGS, $flagSet);
		}
	}

	/**
	 * Returns all properties.
	 *
	 * @return MetadataProperty[]
	 * @phpstan-return array<int, MetadataProperty>
	 */
	public function getAll() : array{
		return $this->properties;
	}

	/**
	 * Returns properties that have changed and need to be broadcasted.
	 *
	 * @return MetadataProperty[]
	 * @phpstan-return array<int, MetadataProperty>
	 */
	public function getDirty() : array{
		return $this->dirtyProperties;
	}

	/**
	 * Clears records of dirty properties.
	 */
	public function clearDirtyProperties() : void{
		$this->dirtyProperties = [];
	}

	/**
	 * Marks the selected property for resending.
	 */
	public function markDirty(int $key) : void{
		if(isset($this->properties[$key])){
			$this->dirtyProperties[$key] = $this->properties[$key];
		}
	}

	/**
	 * Marks all properties for resending.
	 */
	public function markAllDirty() : void{
		$this->dirtyProperties = $this->properties;
	}
}
