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
use pocketmine\network\mcpe\protocol\types\biome\BiomeDefinitionData;
use pocketmine\network\mcpe\protocol\types\biome\BiomeDefinitionEntry;
use pocketmine\network\mcpe\protocol\types\biome\BiomeTagsData;
use function array_map;
use function count;

class BiomeDefinitionListPacket extends DataPacket implements ClientboundPacket{
	public const NETWORK_ID = ProtocolInfo::BIOME_DEFINITION_LIST_PACKET;

	/**
	 * @var BiomeDefinitionEntry[]
	 * @phpstan-var list<BiomeDefinitionEntry>
	 */
	private array $entries;

	/**
	 * @generate-create-func
	 * @param BiomeDefinitionEntry[] $entries
	 * @phpstan-param list<BiomeDefinitionEntry> $entries
	 */
	public static function create(array $entries) : self{
		$result = new self;
		$result->entries = $entries;
		return $result;
	}

	/**
	 * @return BiomeDefinitionEntry[]
	 * @phpstan-return list<BiomeDefinitionEntry>
	 */
	public function getEntries() : array{ return $this->entries; }

	protected function decodePayload(PacketSerializer $in) : void{
		/**
		 * @var BiomeDefinitionData[] $definitionDataByNameIndex
		 * @phpstan-var array<int, BiomeDefinitionData> $definitionDataByNameIndex
		 */
		$definitionDataByNameIndex = [];
		/**
		 * @var string[] $biomeNames
		 * @phpstan-var array<int, string> $biomeNames
		 */
		$biomeNames = [];

		for($i = 0, $count = $in->getUnsignedVarInt(); $i < $count; ++$i){
			$biomeNameIndex = $in->getLShort();

			if(isset($definitionDataByNameIndex[$biomeNameIndex])){
				throw new PacketDecodeException("Repeated biome name index \"$biomeNameIndex\"");
			}

			$definitionDataByNameIndex[$biomeNameIndex] = BiomeDefinitionData::read($in);
		}

		for($i = 0, $count = $in->getUnsignedVarInt(); $i < $count; ++$i){
			$biomeNames[] = $in->getString();
		}

		$this->entries = [];
		foreach($definitionDataByNameIndex as $nameIndex => $data){
			if(!isset($biomeNames[$nameIndex])){
				throw new PacketDecodeException("Biome name index \"$nameIndex\" not found");
			}

			if(($tags = $data->getTags()) === null){
				$stringTags = null;
			}else{
				$stringTags = [];

				foreach($tags->getIndexes() as $tagIndex){
					if(!isset($biomeNames[$tagIndex])){
						throw new PacketDecodeException("Biome tag index \"$tagIndex\" not found");
					}

					$stringTags[] = $biomeNames[$tagIndex];
				}
			}

			$this->entries[] = new BiomeDefinitionEntry(
				$biomeNames[$nameIndex],
				$data->getId(),
				$data->getTemperature(),
				$data->getDownfall(),
				$data->getRedSporeDensity(),
				$data->getBlueSporeDensity(),
				$data->getAshDensity(),
				$data->getWhiteAshDensity(),
				$data->getDepth(),
				$data->getScale(),
				$data->getMapWaterColor(),
				$data->hasRain(),
				$stringTags,
				$data->getChunkGenData(),
			);
		}
	}

	protected function encodePayload(PacketSerializer $out) : void{
		/**
		 * @var int[] $biomeNameIndexes
		 * @phpstan-var array<string, int> $biomeNameIndexes
		 */
		$biomeNameIndexes = [];
		$indexGenerator = function(string $biomeName) use (&$biomeNameIndexes) : int{
			if(isset($biomeNameIndexes[$biomeName])){
				return $biomeNameIndexes[$biomeName];
			}

			$biomeNameIndexes[$biomeName] = count($biomeNameIndexes);
			return $biomeNameIndexes[$biomeName];
		};

		$out->putUnsignedVarInt(count($this->entries));
		foreach($this->entries as $entry){
			$out->putLShort($indexGenerator($entry->getBiomeName()));

			(new BiomeDefinitionData(
				$entry->getId(),
				$entry->getTemperature(),
				$entry->getDownfall(),
				$entry->getRedSporeDensity(),
				$entry->getBlueSporeDensity(),
				$entry->getAshDensity(),
				$entry->getWhiteAshDensity(),
				$entry->getDepth(),
				$entry->getScale(),
				$entry->getMapWaterColor(),
				$entry->hasRain(),
				$entry->getTags() === null ? null : new BiomeTagsData(array_map($indexGenerator, $entry->getTags())),
				$entry->getChunkGenData(),
			))->write($out);
		}

		$out->putUnsignedVarInt(count($biomeNameIndexes));
		foreach($biomeNameIndexes as $biomeName => $index){
			$out->putString($biomeName);
		}
	}

	public function handle(PacketHandlerInterface $handler) : bool{
		return $handler->handleBiomeDefinitionList($this);
	}
}
