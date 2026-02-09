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

use pmmp\encoding\ByteBufferReader;
use pmmp\encoding\ByteBufferWriter;
use pmmp\encoding\VarInt;
use pocketmine\network\mcpe\protocol\types\camera\CameraAimAssistActorPriorityData;
use function count;

class CameraAimAssistActorPriorityPacket extends DataPacket implements ClientboundPacket{
	public const NETWORK_ID = ProtocolInfo::CAMERA_AIM_ASSIST_ACTOR_PRIORITY_PACKET;

	/**
	 * @var CameraAimAssistActorPriorityData[]
	 * @phpstan-var list<CameraAimAssistActorPriorityData>
	 */
	private array $priorityData;

	/**
	 * @generate-create-func
	 * @param CameraAimAssistActorPriorityData[] $priorityData
	 * @phpstan-param list<CameraAimAssistActorPriorityData> $priorityData
	 */
	public static function create(array $priorityData) : self{
		$result = new self;
		$result->priorityData = $priorityData;
		return $result;
	}

	/**
	 * @return CameraAimAssistActorPriorityData[]
	 * @phpstan-return list<CameraAimAssistActorPriorityData>
	 */
	public function getPriorityData() : array{ return $this->priorityData; }

	protected function decodePayload(ByteBufferReader $in) : void{
		$this->priorityData = [];
		for($i = 0, $count = VarInt::readUnsignedInt($in); $i < $count; ++$i){
			$this->priorityData[] = CameraAimAssistActorPriorityData::read($in);
		}
	}

	protected function encodePayload(ByteBufferWriter $out) : void{
		VarInt::writeUnsignedInt($out, count($this->priorityData));
		foreach($this->priorityData as $data){
			$data->write($out);
		}
	}

	public function handle(PacketHandlerInterface $handler) : bool{
		return $handler->handleCameraAimAssistActorPriority($this);
	}
}
