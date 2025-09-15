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
use pocketmine\math\Vector3;
use pocketmine\network\mcpe\protocol\serializer\CommonTypes;

class ClientboundDebugRendererPacket extends DataPacket implements ClientboundPacket{
	public const NETWORK_ID = ProtocolInfo::CLIENTBOUND_DEBUG_RENDERER_PACKET;

	public const TYPE_CLEAR = 1;
	public const TYPE_ADD_CUBE = 2;

	private int $type;

	//TODO: if more types are added, we'll probably want to make a separate data type and interfaces
	private string $text;
	private Vector3 $position;
	private float $red;
	private float $green;
	private float $blue;
	private float $alpha;
	private int $durationMillis;

	private static function base(int $type) : self{
		$result = new self;
		$result->type = $type;
		return $result;
	}

	public static function clear() : self{ return self::base(self::TYPE_CLEAR); }

	public static function addCube(string $text, Vector3 $position, float $red, float $green, float $blue, float $alpha, int $durationMillis) : self{
		$result = self::base(self::TYPE_ADD_CUBE);
		$result->text = $text;
		$result->position = $position;
		$result->red = $red;
		$result->green = $green;
		$result->blue = $blue;
		$result->alpha = $alpha;
		$result->durationMillis = $durationMillis;
		return $result;
	}

	public function getType() : int{ return $this->type; }

	public function getText() : string{ return $this->text; }

	public function getPosition() : Vector3{ return $this->position; }

	public function getRed() : float{ return $this->red; }

	public function getGreen() : float{ return $this->green; }

	public function getBlue() : float{ return $this->blue; }

	public function getAlpha() : float{ return $this->alpha; }

	public function getDurationMillis() : int{ return $this->durationMillis; }

	protected function decodePayload(ByteBufferReader $in) : void{
		$this->type = LE::readUnsignedInt($in);

		switch($this->type){
			case self::TYPE_CLEAR:
				//NOOP
				break;
			case self::TYPE_ADD_CUBE:
				$this->text = CommonTypes::getString($in);
				$this->position = CommonTypes::getVector3($in);
				$this->red = LE::readFloat($in);
				$this->green = LE::readFloat($in);
				$this->blue = LE::readFloat($in);
				$this->alpha = LE::readFloat($in);
				$this->durationMillis = LE::readUnsignedLong($in);
				break;
			default:
				throw new PacketDecodeException("Unknown type " . $this->type);
		}
	}

	protected function encodePayload(ByteBufferWriter $out) : void{
		LE::writeUnsignedInt($out, $this->type);

		switch($this->type){
			case self::TYPE_CLEAR:
				//NOOP
				break;
			case self::TYPE_ADD_CUBE:
				CommonTypes::putString($out, $this->text);
				CommonTypes::putVector3($out, $this->position);
				LE::writeFloat($out, $this->red);
				LE::writeFloat($out, $this->green);
				LE::writeFloat($out, $this->blue);
				LE::writeFloat($out, $this->alpha);
				LE::writeUnsignedLong($out, $this->durationMillis);
				break;
			default:
				throw new \InvalidArgumentException("Unknown type " . $this->type);
		}
	}

	public function handle(PacketHandlerInterface $handler) : bool{
		return $handler->handleClientboundDebugRenderer($this);
	}
}
