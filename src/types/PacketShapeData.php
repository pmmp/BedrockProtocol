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

use pocketmine\color\Color;
use pocketmine\math\Vector3;
use pocketmine\network\mcpe\protocol\serializer\PacketSerializer;
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

	public static function read(PacketSerializer $in) : self{
		$networkId = $in->getUnsignedVarLong();
		$type = $in->readOptional(fn() => ScriptDebugShapeType::fromPacket($in->getByte()));
		$location = $in->readOptional($in->getVector3(...));
		$scale = $in->readOptional($in->getLFloat(...));
		$rotation = $in->readOptional($in->getVector3(...));
		$totalTimeLeft = $in->readOptional($in->getLFloat(...));
		$color = $in->readOptional(fn() => Color::fromARGB($in->getLInt()));
		$text = $in->readOptional($in->getString(...));
		$boxBound = $in->readOptional($in->getVector3(...));
		$lineEndLocation = $in->readOptional($in->getVector3(...));
		$arrowHeadLength = $in->readOptional($in->getLFloat(...));
		$arrowHeadRadius = $in->readOptional($in->getLFloat(...));
		$segments = $in->readOptional($in->getByte(...));

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

	public function write(PacketSerializer $out) : void{
		$out->writeOptional($this->networkId, $out->putUnsignedVarLong(...));
		$out->writeOptional($this->type, fn(ScriptDebugShapeType $type) => $out->putByte($type->value));
		$out->writeOptional($this->location, $out->putVector3(...));
		$out->writeOptional($this->scale, $out->putLFloat(...));
		$out->writeOptional($this->rotation, $out->putVector3(...));
		$out->writeOptional($this->totalTimeLeft, $out->putLFloat(...));
		$out->writeOptional($this->color, fn(Color $color) => $out->putLInt($color->toARGB()));
		$out->writeOptional($this->text, $out->putString(...));
		$out->writeOptional($this->boxBound, $out->putVector3(...));
		$out->writeOptional($this->lineEndLocation, $out->putVector3(...));
		$out->writeOptional($this->arrowHeadLength, $out->putLFloat(...));
		$out->writeOptional($this->arrowHeadRadius, $out->putLFloat(...));
		$out->writeOptional($this->segments, $out->putByte(...));
	}
}
