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

use pmmp\encoding\Byte;
use pmmp\encoding\ByteBufferReader;
use pmmp\encoding\ByteBufferWriter;
use pmmp\encoding\VarInt;
use pocketmine\network\mcpe\protocol\types\camera\CameraAimAssistCategory;
use pocketmine\network\mcpe\protocol\types\camera\CameraAimAssistPreset;
use function count;

class CameraAimAssistPresetsPacket extends DataPacket implements ClientboundPacket{
	public const NETWORK_ID = ProtocolInfo::CAMERA_AIM_ASSIST_PRESETS_PACKET;

	/** @var CameraAimAssistCategory[] */
	private array $categories;
	/** @var CameraAimAssistPreset[] */
	private array $presets;
	private int $operation;

	/**
	 * @generate-create-func
	 * @param CameraAimAssistCategory[] $categories
	 * @param CameraAimAssistPreset[]   $presets
	 */
	public static function create(array $categories, array $presets, int $operation) : self{
		$result = new self;
		$result->categories = $categories;
		$result->presets = $presets;
		$result->operation = $operation;
		return $result;
	}

	/**
	 * @return CameraAimAssistCategory[]
	 */
	public function getCategories() : array{ return $this->categories; }

	/**
	 * @return CameraAimAssistPreset[]
	 */
	public function getPresets() : array{ return $this->presets; }

	public function getOperation() : int{ return $this->operation; }

	protected function decodePayload(ByteBufferReader $in) : void{
		$this->categories = [];
		for($i = 0, $count = VarInt::readUnsignedInt($in); $i < $count; ++$i){
			$this->categories[] = CameraAimAssistCategory::read($in);
		}

		$this->presets = [];
		for($i = 0, $count = VarInt::readUnsignedInt($in); $i < $count; ++$i){
			$this->presets[] = CameraAimAssistPreset::read($in);
		}

		$this->operation = Byte::readUnsigned($in);
	}

	protected function encodePayload(ByteBufferWriter $out) : void{
		VarInt::writeUnsignedInt($out, count($this->categories));
		foreach($this->categories as $category){
			$category->write($out);
		}

		VarInt::writeUnsignedInt($out, count($this->presets));
		foreach($this->presets as $preset){
			$preset->write($out);
		}

		Byte::writeUnsigned($out, $this->operation);
	}

	public function handle(PacketHandlerInterface $handler) : bool{
		return $handler->handleCameraAimAssistPresets($this);
	}
}
