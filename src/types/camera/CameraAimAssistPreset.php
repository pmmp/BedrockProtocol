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

final class CameraAimAssistPreset{

	/**
	 * @param string[] $exclusionList
	 * @param string[] $liquidTargetingList
	 * @param CameraAimAssistPresetItemSettings[] $itemSettings
	 */
	public function __construct(
		private string $identifier,
		private array $exclusionList,
		private array $liquidTargetingList,
		private array $itemSettings,
		private ?string $defaultItemSettings,
		private ?string $defaultHandSettings,
	){}

	public function getIdentifier() : string{ return $this->identifier; }

	/**
	 * @return string[]
	 */
	public function getExclusionList() : array{ return $this->exclusionList; }

	/**
	 * @return string[]
	 */
	public function getLiquidTargetingList() : array{ return $this->liquidTargetingList; }

	/**
	 * @return CameraAimAssistPresetItemSettings[]
	 */
	public function getItemSettings() : array{ return $this->itemSettings; }

	public function getDefaultItemSettings() : ?string{ return $this->defaultItemSettings; }

	public function getDefaultHandSettings() : ?string{ return $this->defaultHandSettings; }

	public static function read(PacketSerializer $in) : self{
		$identifier = $in->getString();

		$exclusionList = [];
		for($i = 0, $len = $in->getUnsignedVarInt(); $i < $len; ++$i){
			$exclusionList[] = $in->getString();
		}

		$liquidTargetingList = [];
		for($i = 0, $len = $in->getUnsignedVarInt(); $i < $len; ++$i){
			$liquidTargetingList[] = $in->getString();
		}

		$itemSettings = [];
		for($i = 0, $len = $in->getUnsignedVarInt(); $i < $len; ++$i){
			$itemSettings[] = CameraAimAssistPresetItemSettings::read($in);
		}

		$defaultItemSettings = $in->readOptional(fn() => $in->getString());
		$defaultHandSettings = $in->readOptional(fn() => $in->getString());

		return new self(
			$identifier,
			$exclusionList,
			$liquidTargetingList,
			$itemSettings,
			$defaultItemSettings,
			$defaultHandSettings
		);
	}

	public function write(PacketSerializer $out) : void{
		$out->putString($this->identifier);

		$out->putUnsignedVarInt(count($this->exclusionList));
		foreach($this->exclusionList as $exclusion){
			$out->putString($exclusion);
		}

		$out->putUnsignedVarInt(count($this->liquidTargetingList));
		foreach($this->liquidTargetingList as $liquidTargeting){
			$out->putString($liquidTargeting);
		}

		$out->putUnsignedVarInt(count($this->itemSettings));
		foreach($this->itemSettings as $itemSetting){
			$itemSetting->write($out);
		}

		$out->writeOptional($this->defaultItemSettings, fn(string $v) => $out->putString($v));
		$out->writeOptional($this->defaultHandSettings, fn(string $v) => $out->putString($v));
	}
}
