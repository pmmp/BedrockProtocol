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
use pocketmine\network\mcpe\protocol\serializer\CommonTypes;
use pocketmine\network\mcpe\protocol\types\TextureShiftAction;
use function count;

class ClientboundTextureShiftPacket extends DataPacket implements ClientboundPacket{
	public const NETWORK_ID = ProtocolInfo::CLIENTBOUND_TEXTURE_SHIFT_PACKET;

	/** @see TextureShiftAction */
	private int $actionId;
	private string $collectionName;
	private string $fromStep;
	private string $toStep;
	/**
	 * @var string[]
	 * @phpstan-var list<string>
	 */
	private array $allSteps;
	private int $currentLengthTicks;
	private int $totalLengthTicks;
	private bool $enabled;

	/**
	 * @generate-create-func
	 * @param string[] $allSteps
	 * @phpstan-param list<string> $allSteps
	 */
	public static function create(
		int $actionId,
		string $collectionName,
		string $fromStep,
		string $toStep,
		array $allSteps,
		int $currentLengthTicks,
		int $totalLengthTicks,
		bool $enabled,
	) : self{
		$result = new self;
		$result->actionId = $actionId;
		$result->collectionName = $collectionName;
		$result->fromStep = $fromStep;
		$result->toStep = $toStep;
		$result->allSteps = $allSteps;
		$result->currentLengthTicks = $currentLengthTicks;
		$result->totalLengthTicks = $totalLengthTicks;
		$result->enabled = $enabled;
		return $result;
	}

	/**
	 * @see TextureShiftAction
	 */
	public function getActionId() : int{ return $this->actionId; }

	public function getCollectionName() : string{ return $this->collectionName; }

	public function getFromStep() : string{ return $this->fromStep; }

	public function getToStep() : string{ return $this->toStep; }

	/**
	 * @return string[]
	 * @phpstan-return list<string>
	 */
	public function getAllSteps() : array{ return $this->allSteps; }

	public function getCurrentLengthTicks() : int{ return $this->currentLengthTicks; }

	public function getTotalLengthTicks() : int{ return $this->totalLengthTicks; }

	public function isEnabled() : bool{ return $this->enabled; }

	protected function decodePayload(ByteBufferReader $in) : void{
		$this->actionId = Byte::readUnsigned($in);
		$this->collectionName = CommonTypes::getString($in);
		$this->fromStep = CommonTypes::getString($in);
		$this->toStep = CommonTypes::getString($in);

		for($i = 0, $count = VarInt::readUnsignedInt($in); $i < $count; ++$i){
			$this->allSteps[] = CommonTypes::getString($in);
		}

		$this->currentLengthTicks = VarInt::readUnsignedLong($in);
		$this->totalLengthTicks = VarInt::readUnsignedLong($in);
		$this->enabled = CommonTypes::getBool($in);
	}

	protected function encodePayload(ByteBufferWriter $out) : void{
		Byte::writeUnsigned($out, $this->actionId);
		CommonTypes::putString($out, $this->collectionName);
		CommonTypes::putString($out, $this->fromStep);
		CommonTypes::putString($out, $this->toStep);

		VarInt::writeUnsignedInt($out, count($this->allSteps));
		foreach($this->allSteps as $step){
			CommonTypes::putString($out, $step);
		}

		VarInt::writeUnsignedLong($out, $this->currentLengthTicks);
		VarInt::writeUnsignedLong($out, $this->totalLengthTicks);
		CommonTypes::putBool($out, $this->enabled);
	}

	public function handle(PacketHandlerInterface $handler) : bool{
		return $handler->handleClientboundTextureShift($this);
	}
}
