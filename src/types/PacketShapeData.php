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

namespace pocketmine\network\mcpe\protocol\types;

use pmmp\encoding\Byte;
use pmmp\encoding\ByteBufferReader;
use pmmp\encoding\ByteBufferWriter;
use pmmp\encoding\LE;
use pmmp\encoding\VarInt;
use pocketmine\color\Color;
use pocketmine\math\Vector3;
use pocketmine\network\mcpe\protocol\serializer\CommonTypes;
use pocketmine\network\mcpe\protocol\ServerScriptDebugDrawerPacket;

/**
 * @see ServerScriptDebugDrawerPacket
 */
final class PacketShapeData{

	public function __construct(
		private int $networkId,
		private ?ScriptDebugShapeType $type,
		private ?Vector3 $location,
		private ?float $scale,
		private ?Vector3 $rotation,
		private ?float $totalTimeLeft,
		private ?Color $color,
		private ?string $text,
		private ?Vector3 $boxBound,
		private ?Vector3 $lineEndLocation,
		private ?float $arrowHeadLength,
		private ?float $arrowHeadRadius,
		private ?int $segments,
	){}

	public static function remove(int $networkId) : self{
		return new self($networkId, null, null, null, null, null, null, null, null, null, null, null, null);
	}

	public static function line(int $networkId, Vector3 $location, Vector3 $lineEndLocation, ?Color $color = null) : self{
		return new self(
			networkId: $networkId,
			type: ScriptDebugShapeType::LINE,
			location: $location,
			scale: null,
			rotation: null,
			totalTimeLeft: null,
			color: $color,
			text: null,
			boxBound: null,
			lineEndLocation: $lineEndLocation,
			arrowHeadLength: null,
			arrowHeadRadius: null,
			segments: null
		);
	}

	public static function box(int $networkId, Vector3 $location, Vector3 $boxBound, ?float $scale = null, ?Color $color = null) : self{
		return new self(
			networkId: $networkId,
			type: ScriptDebugShapeType::BOX,
			location: $location,
			scale: $scale,
			rotation: null,
			totalTimeLeft: null,
			color: $color,
			text: null,
			boxBound: $boxBound,
			lineEndLocation: null,
			arrowHeadLength: null,
			arrowHeadRadius: null,
			segments: null
		);
	}

	public static function sphere(int $networkId, Vector3 $location, ?float $scale = null, ?Color $color = null, ?int $segments = null) : self{
		return new self(
			networkId: $networkId,
			type: ScriptDebugShapeType::SPHERE,
			location: $location,
			scale: $scale,
			rotation: null,
			totalTimeLeft: null,
			color: $color,
			text: null,
			boxBound: null,
			lineEndLocation: null,
			arrowHeadLength: null,
			arrowHeadRadius: null,
			segments: $segments
		);
	}

	public static function circle(int $networkId, Vector3 $location, ?float $scale = null, ?Color $color = null, ?int $segments = null) : self{
		return new self(
			networkId: $networkId,
			type: ScriptDebugShapeType::CIRCLE,
			location: $location,
			scale: $scale,
			rotation: null,
			totalTimeLeft: null,
			color: $color,
			text: null,
			boxBound: null,
			lineEndLocation: null,
			arrowHeadLength: null,
			arrowHeadRadius: null,
			segments: $segments
		);
	}

	public static function text(int $networkId, Vector3 $location, string $text, ?Color $color = null) : self{
		return new self(
			networkId: $networkId,
			type: ScriptDebugShapeType::TEXT,
			location: $location,
			scale: null,
			rotation: null,
			totalTimeLeft: null,
			color: $color,
			text: $text,
			boxBound: null,
			lineEndLocation: null,
			arrowHeadLength: null,
			arrowHeadRadius: null,
			segments: null
		);
	}

	public static function arrow(int $networkId, Vector3 $location, Vector3 $lineEndLocation, ?float $scale = null, ?Color $color = null, ?float $arrowHeadLength = null, ?float $arrowHeadRadius = null, ?int $segments = null) : self{
		return new self(
			networkId: $networkId,
			type: ScriptDebugShapeType::ARROW,
			location: $location,
			scale: $scale,
			rotation: null,
			totalTimeLeft: null,
			color: $color,
			text: null,
			boxBound: null,
			lineEndLocation: $lineEndLocation,
			arrowHeadLength: $arrowHeadLength,
			arrowHeadRadius: $arrowHeadRadius,
			segments: $segments
		);
	}

	public function getNetworkId() : int{ return $this->networkId; }

	public function getType() : ?ScriptDebugShapeType{ return $this->type; }

	public function getLocation() : ?Vector3{ return $this->location; }

	public function getScale() : ?float{ return $this->scale; }

	public function getRotation() : ?Vector3{ return $this->rotation; }

	public function getTotalTimeLeft() : ?float{ return $this->totalTimeLeft; }

	public function getColor() : ?Color{ return $this->color; }

	public function getText() : ?string{ return $this->text; }

	public function getBoxBound() : ?Vector3{ return $this->boxBound; }

	public function getLineEndLocation() : ?Vector3{ return $this->lineEndLocation; }

	public function getArrowHeadLength() : ?float{ return $this->arrowHeadLength; }

	public function getArrowHeadRadius() : ?float{ return $this->arrowHeadRadius; }

	public function getSegments() : ?int{ return $this->segments; }

	public static function read(ByteBufferReader $in) : self{
		$networkId = VarInt::readUnsignedLong($in);
		$type = CommonTypes::readOptional($in, fn() => ScriptDebugShapeType::fromPacket(Byte::readUnsigned($in)));
		$location = CommonTypes::readOptional($in, CommonTypes::getVector3(...));
		$scale = CommonTypes::readOptional($in, LE::readFloat(...));
		$rotation = CommonTypes::readOptional($in, CommonTypes::getVector3(...));
		$totalTimeLeft = CommonTypes::readOptional($in, LE::readFloat(...));
		$color = CommonTypes::readOptional($in, fn() => Color::fromARGB(LE::readUnsignedInt($in)));
		$text = CommonTypes::readOptional($in, CommonTypes::getString(...));
		$boxBound = CommonTypes::readOptional($in, CommonTypes::getVector3(...));
		$lineEndLocation = CommonTypes::readOptional($in, CommonTypes::getVector3(...));
		$arrowHeadLength = CommonTypes::readOptional($in, LE::readFloat(...));
		$arrowHeadRadius = CommonTypes::readOptional($in, LE::readFloat(...));
		$segments = CommonTypes::readOptional($in, Byte::readUnsigned(...));

		return new self(
			$networkId,
			$type,
			$location,
			$scale,
			$rotation,
			$totalTimeLeft,
			$color,
			$text,
			$boxBound,
			$lineEndLocation,
			$arrowHeadLength,
			$arrowHeadRadius,
			$segments
		);
	}

	public function write(ByteBufferWriter $out) : void{
		VarInt::writeUnsignedLong($out, $this->networkId);
		CommonTypes::writeOptional($out, $this->type, fn(ByteBufferWriter $out, ScriptDebugShapeType $type) => Byte::writeUnsigned($out, $type->value));
		CommonTypes::writeOptional($out, $this->location, CommonTypes::putVector3(...));
		CommonTypes::writeOptional($out, $this->scale, LE::writeFloat(...));
		CommonTypes::writeOptional($out, $this->rotation, CommonTypes::putVector3(...));
		CommonTypes::writeOptional($out, $this->totalTimeLeft, LE::writeFloat(...));
		CommonTypes::writeOptional($out, $this->color, fn(ByteBufferWriter $out, Color $color) => LE::writeUnsignedInt($out, $color->toARGB()));
		CommonTypes::writeOptional($out, $this->text, CommonTypes::putString(...));
		CommonTypes::writeOptional($out, $this->boxBound, CommonTypes::putVector3(...));
		CommonTypes::writeOptional($out, $this->lineEndLocation, CommonTypes::putVector3(...));
		CommonTypes::writeOptional($out, $this->arrowHeadLength, LE::writeFloat(...));
		CommonTypes::writeOptional($out, $this->arrowHeadRadius, LE::writeFloat(...));
		CommonTypes::writeOptional($out, $this->segments, Byte::writeUnsigned(...));
	}
}
