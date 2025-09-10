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
use pocketmine\network\mcpe\protocol\serializer\CommonTypes;

/**
 * Handled only in Education mode. Used to fire telemetry reporting on the client.
 */
class LessonProgressPacket extends DataPacket implements ClientboundPacket{
	public const NETWORK_ID = ProtocolInfo::LESSON_PROGRESS_PACKET;

	public const ACTION_START = 0;
	public const ACTION_FINISH = 1;
	public const ACTION_RESTART = 2;

	private int $action;
	private int $score;
	private string $activityId;

	/**
	 * @generate-create-func
	 */
	public static function create(int $action, int $score, string $activityId) : self{
		$result = new self;
		$result->action = $action;
		$result->score = $score;
		$result->activityId = $activityId;
		return $result;
	}

	public function getAction() : int{ return $this->action; }

	public function getScore() : int{ return $this->score; }

	public function getActivityId() : string{ return $this->activityId; }

	protected function decodePayload(ByteBufferReader $in) : void{
		$this->action = VarInt::readSignedInt($in);
		$this->score = VarInt::readSignedInt($in);
		$this->activityId = CommonTypes::getString($in);
	}

	protected function encodePayload(ByteBufferWriter $out) : void{
		VarInt::writeSignedInt($out, $this->action);
		VarInt::writeSignedInt($out, $this->score);
		CommonTypes::putString($out, $this->activityId);
	}

	public function handle(PacketHandlerInterface $handler) : bool{
		return $handler->handleLessonProgress($this);
	}
}
