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
use pmmp\encoding\LE;
use pmmp\encoding\VarInt;
use pocketmine\network\mcpe\protocol\serializer\CommonTypes;
use function count;

class AnimateEntityPacket extends DataPacket implements ClientboundPacket{
	public const NETWORK_ID = ProtocolInfo::ANIMATE_ENTITY_PACKET;

	private string $animation;
	private string $nextState;
	private string $stopExpression;
	private int $stopExpressionVersion;
	private string $controller;
	private float $blendOutTime;
	/**
	 * @var int[]
	 * @phpstan-var list<int>
	 */
	private array $actorRuntimeIds;

	/**
	 * @generate-create-func
	 * @param int[] $actorRuntimeIds
	 * @phpstan-param list<int> $actorRuntimeIds
	 */
	public static function create(
		string $animation,
		string $nextState,
		string $stopExpression,
		int $stopExpressionVersion,
		string $controller,
		float $blendOutTime,
		array $actorRuntimeIds,
	) : self{
		$result = new self;
		$result->animation = $animation;
		$result->nextState = $nextState;
		$result->stopExpression = $stopExpression;
		$result->stopExpressionVersion = $stopExpressionVersion;
		$result->controller = $controller;
		$result->blendOutTime = $blendOutTime;
		$result->actorRuntimeIds = $actorRuntimeIds;
		return $result;
	}

	public function getAnimation() : string{ return $this->animation; }

	public function getNextState() : string{ return $this->nextState; }

	public function getStopExpression() : string{ return $this->stopExpression; }

	public function getStopExpressionVersion() : int{ return $this->stopExpressionVersion; }

	public function getController() : string{ return $this->controller; }

	public function getBlendOutTime() : float{ return $this->blendOutTime; }

	/**
	 * @return int[]
	 * @phpstan-return list<int>
	 */
	public function getActorRuntimeIds() : array{ return $this->actorRuntimeIds; }

	protected function decodePayload(ByteBufferReader $in) : void{
		$this->animation = CommonTypes::getString($in);
		$this->nextState = CommonTypes::getString($in);
		$this->stopExpression = CommonTypes::getString($in);
		$this->stopExpressionVersion = LE::readSignedInt($in);
		$this->controller = CommonTypes::getString($in);
		$this->blendOutTime = LE::readFloat($in);
		$this->actorRuntimeIds = [];
		for($i = 0, $len = VarInt::readUnsignedInt($in); $i < $len; ++$i){
			$this->actorRuntimeIds[] = CommonTypes::getActorRuntimeId($in);
		}
	}

	protected function encodePayload(ByteBufferWriter $out) : void{
		CommonTypes::putString($out, $this->animation);
		CommonTypes::putString($out, $this->nextState);
		CommonTypes::putString($out, $this->stopExpression);
		LE::writeSignedInt($out, $this->stopExpressionVersion);
		CommonTypes::putString($out, $this->controller);
		LE::writeFloat($out, $this->blendOutTime);
		VarInt::writeUnsignedInt($out, count($this->actorRuntimeIds));
		foreach($this->actorRuntimeIds as $id){
			CommonTypes::putActorRuntimeId($out, $id);
		}
	}

	public function handle(PacketHandlerInterface $handler) : bool{
		return $handler->handleAnimateEntity($this);
	}
}
