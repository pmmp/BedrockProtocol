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
use pocketmine\network\mcpe\protocol\types\camera\CameraSplineDefinition;
use function count;

class CameraSplinePacket extends DataPacket implements ClientboundPacket{
	public const NETWORK_ID = ProtocolInfo::CAMERA_SPLINE_PACKET;

	/**
	 * @var CameraSplineDefinition[]
	 * @phpstan-var list<CameraSplineDefinition>
	 */
	private array $splines;

	/**
	 * @generate-create-func
	 * @param CameraSplineDefinition[] $splines
	 * @phpstan-param list<CameraSplineDefinition> $splines
	 */
	public static function create(array $splines) : self{
		$result = new self;
		$result->splines = $splines;
		return $result;
	}

	/**
	 * @return CameraSplineDefinition[]
	 * @phpstan-return list<CameraSplineDefinition>
	 */
	public function getSplines() : array{ return $this->splines; }

	protected function decodePayload(ByteBufferReader $in) : void{
		$this->splines = [];
		for($i = 0, $splineCount = VarInt::readUnsignedInt($in); $i < $splineCount; ++$i){
			$this->splines[] = CameraSplineDefinition::read($in);
		}
	}

	protected function encodePayload(ByteBufferWriter $out) : void{
		VarInt::writeUnsignedInt($out, count($this->splines));
		foreach($this->splines as $spline){
			$spline->write($out);
		}
	}

	public function handle(PacketHandlerInterface $handler) : bool{
		return $handler->handleCameraSpline($this);
	}
}
