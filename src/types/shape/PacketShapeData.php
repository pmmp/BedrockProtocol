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

namespace pocketmine\network\mcpe\protocol\types\shape;

use pmmp\encoding\Byte;
use pmmp\encoding\ByteBufferReader;
use pmmp\encoding\ByteBufferWriter;
use pmmp\encoding\LE;
use pmmp\encoding\VarInt;
use pocketmine\color\Color;
use pocketmine\math\Vector2;
use pocketmine\math\Vector3;
use pocketmine\network\mcpe\protocol\PacketDecodeException;
use pocketmine\network\mcpe\protocol\PrimitiveShapesPacket;
use pocketmine\network\mcpe\protocol\serializer\CommonTypes;

/**
 * @see PrimitiveShapesPacket
 */
final class PacketShapeData{

	public function __construct(
		private int $networkId,
		private ?PrimitiveShapeType $type,
		private ?Vector3 $location,
		private ?float $scale,
		private ?Vector3 $rotation,
		private ?float $totalTimeLeft,
		private ?float $maximumRenderDistance,
		private ?Color $color,
		private ?int $dimensionId,
		private ?int $attachedToEntityId,
		private ?PrimitiveShapePayload $payload,
	){}

	public static function remove(int $networkId, ?int $dimensionId = null) : self{
		return new self($networkId, null, null, null, null, null, null, null, $dimensionId, null, null);
	}

	public static function line(int $networkId, Vector3 $location, Vector3 $lineEndLocation, ?Color $color = null, ?int $dimensionId = null, ?int $attachedToEntityId = null) : self{
		return new self(
			networkId: $networkId,
			type: PrimitiveShapeType::LINE,
			location: $location,
			scale: null,
			rotation: null,
			totalTimeLeft: null,
			maximumRenderDistance: null,
			color: $color,
			dimensionId: $dimensionId,
			attachedToEntityId: $attachedToEntityId,
			payload: new PrimitiveShapeLinePayload($lineEndLocation)
		);
	}

	public static function box(int $networkId, Vector3 $location, Vector3 $boxBound, ?float $scale = null, ?Color $color = null, ?int $dimensionId = null, ?int $attachedToEntityId = null) : self{
		return new self(
			networkId: $networkId,
			type: PrimitiveShapeType::BOX,
			location: $location,
			scale: $scale,
			rotation: null,
			totalTimeLeft: null,
			maximumRenderDistance: null,
			color: $color,
			dimensionId: $dimensionId,
			attachedToEntityId: $attachedToEntityId,
			payload: new PrimitiveShapeBoxPayload($boxBound)
		);
	}

	public static function sphere(int $networkId, Vector3 $location, int $segments, ?float $scale = null, ?Color $color = null, ?int $dimensionId = null, ?int $attachedToEntityId = null) : self{
		return new self(
			networkId: $networkId,
			type: PrimitiveShapeType::SPHERE,
			location: $location,
			scale: $scale,
			rotation: null,
			totalTimeLeft: null,
			maximumRenderDistance: null,
			color: $color,
			dimensionId: $dimensionId,
			attachedToEntityId: $attachedToEntityId,
			payload: new PrimitiveShapeCircleOrSpherePayload($segments)
		);
	}

	public static function circle(int $networkId, Vector3 $location, int $segments, ?float $scale = null, ?Color $color = null, ?int $dimensionId = null, ?int $attachedToEntityId = null) : self{
		return new self(
			networkId: $networkId,
			type: PrimitiveShapeType::CIRCLE,
			location: $location,
			scale: $scale,
			rotation: null,
			totalTimeLeft: null,
			maximumRenderDistance: null,
			color: $color,
			dimensionId: $dimensionId,
			attachedToEntityId: $attachedToEntityId,
			payload: new PrimitiveShapeCircleOrSpherePayload($segments)
		);
	}

	public static function text(int $networkId, Vector3 $location, string $text, bool $useRotation = false, ?Color $backgroundColor = null, bool $depthTest = true, bool $showBackface = true, bool $showTextBackface = true, ?Color $color = null, ?int $dimensionId = null, ?int $attachedToEntityId = null) : self{
		return new self(
			networkId: $networkId,
			type: PrimitiveShapeType::TEXT,
			location: $location,
			scale: null,
			rotation: null,
			totalTimeLeft: null,
			maximumRenderDistance: null,
			color: $color,
			dimensionId: $dimensionId,
			attachedToEntityId: $attachedToEntityId,
			payload: new PrimitiveShapeTextPayload($text, $useRotation, $backgroundColor, $depthTest, $showBackface, $showTextBackface)
		);
	}

	public static function arrow(int $networkId, Vector3 $location, Vector3 $lineEndLocation, ?float $scale = null, ?Color $color = null, ?float $arrowHeadLength = null, ?float $arrowHeadRadius = null, ?int $segments = null, ?int $dimensionId = null, ?int $attachedToEntityId = null) : self{
		return new self(
			networkId: $networkId,
			type: PrimitiveShapeType::ARROW,
			location: $location,
			scale: $scale,
			rotation: null,
			totalTimeLeft: null,
			maximumRenderDistance: null,
			color: $color,
			dimensionId: $dimensionId,
			attachedToEntityId: $attachedToEntityId,
			payload: new PrimitiveShapeArrowPayload($lineEndLocation, $arrowHeadLength, $arrowHeadRadius, $segments)
		);
	}

	public static function cylinder(int $networkId, Vector3 $location, Vector2 $radiusX, Vector2 $radiusZ, float $height, int $segments, ?Color $color = null, ?int $dimensionId = null, ?int $attachedToEntityId = null) : self{
		return new self(
			networkId: $networkId,
			type: PrimitiveShapeType::CYLINDER,
			location: $location,
			scale: null,
			rotation: null,
			totalTimeLeft: null,
			maximumRenderDistance: null,
			color: $color,
			dimensionId: $dimensionId,
			attachedToEntityId: $attachedToEntityId,
			payload: new PrimitiveShapeCylinderPayload($radiusX, $radiusZ, $height, $segments)
		);
	}

	public static function pyramid(int $networkId, Vector3 $location, float $width, float $height, ?float $depth = null, ?Color $color = null, ?int $dimensionId = null, ?int $attachedToEntityId = null) : self{
		return new self(
			networkId: $networkId,
			type: PrimitiveShapeType::PYRAMID,
			location: $location,
			scale: null,
			rotation: null,
			totalTimeLeft: null,
			maximumRenderDistance: null,
			color: $color,
			dimensionId: $dimensionId,
			attachedToEntityId: $attachedToEntityId,
			payload: new PrimitiveShapePyramidPayload($width, $depth, $height)
		);
	}

	public static function ellipsoid(int $networkId, Vector3 $location, Vector3 $radii, int $segmentsPerAxis, ?Color $color = null, ?int $dimensionId = null, ?int $attachedToEntityId = null) : self{
		return new self(
			networkId: $networkId,
			type: PrimitiveShapeType::ELLIPSOID,
			location: $location,
			scale: null,
			rotation: null,
			totalTimeLeft: null,
			maximumRenderDistance: null,
			color: $color,
			dimensionId: $dimensionId,
			attachedToEntityId: $attachedToEntityId,
			payload: new PrimitiveShapeEllipsoidPayload($radii, $segmentsPerAxis)
		);
	}

	public static function cone(int $networkId, Vector3 $location, Vector2 $radii, float $height, int $segments, ?Color $color = null, ?int $dimensionId = null, ?int $attachedToEntityId = null) : self{
		return new self(
			networkId: $networkId,
			type: PrimitiveShapeType::CONE,
			location: $location,
			scale: null,
			rotation: null,
			totalTimeLeft: null,
			maximumRenderDistance: null,
			color: $color,
			dimensionId: $dimensionId,
			attachedToEntityId: $attachedToEntityId,
			payload: new PrimitiveShapeConePayload($radii, $height, $segments)
		);
	}

	public function getNetworkId() : int{ return $this->networkId; }

	public function getType() : ?PrimitiveShapeType{ return $this->type; }

	public function getLocation() : ?Vector3{ return $this->location; }

	public function getScale() : ?float{ return $this->scale; }

	public function getRotation() : ?Vector3{ return $this->rotation; }

	public function getTotalTimeLeft() : ?float{ return $this->totalTimeLeft; }

	public function getMaximumRenderDistance() : ?float{ return $this->maximumRenderDistance; }

	public function getColor() : ?Color{ return $this->color; }

	public function getDimensionId() : ?int{ return $this->dimensionId; }

	public function getAttachedToEntityId() : ?int{ return $this->attachedToEntityId; }

	public function getPayload() : ?PrimitiveShapePayload{ return $this->payload; }

	public static function read(ByteBufferReader $in) : self{
		$networkId = VarInt::readUnsignedLong($in);
		$shapeType = CommonTypes::readOptional($in, fn() => PrimitiveShapeType::fromPacket(Byte::readUnsigned($in)));
		$location = CommonTypes::readOptional($in, CommonTypes::getVector3(...));
		$scale = CommonTypes::readOptional($in, LE::readFloat(...));
		$rotation = CommonTypes::readOptional($in, CommonTypes::getVector3(...));
		$totalTimeLeft = CommonTypes::readOptional($in, LE::readFloat(...));
		$maximumRenderDistance = CommonTypes::readOptional($in, LE::readFloat(...));
		$color = CommonTypes::readOptional($in, fn() => Color::fromARGB(LE::readUnsignedInt($in)));
		$dimensionId = CommonTypes::readOptional($in, fn() => VarInt::readSignedInt($in));
		$attachedToEntityId = CommonTypes::readOptional($in, fn() => CommonTypes::getActorRuntimeId($in));

		$payloadType = VarInt::readUnsignedInt($in);
		//WTF IS THIS HORROR SHOW
		if(
			($shapeType !== null && $payloadType !== $shapeType->getPayloadType() && $payloadType !== PrimitiveShapeType::PAYLOAD_TYPE_NONE) ||
			($shapeType === null && $payloadType !== PrimitiveShapeType::PAYLOAD_TYPE_NONE)
		){
			throw new PacketDecodeException("Unexpected payload type $payloadType for provided shape type " . ($shapeType->name ?? "(not set)"));
		}

		$payload = match($payloadType){
			PrimitiveShapeType::PAYLOAD_TYPE_NONE => null,
			PrimitiveShapeType::PAYLOAD_TYPE_ARROW => PrimitiveShapeArrowPayload::read($in),
			PrimitiveShapeType::PAYLOAD_TYPE_TEXT => PrimitiveShapeTextPayload::read($in),
			PrimitiveShapeType::PAYLOAD_TYPE_BOX => PrimitiveShapeBoxPayload::read($in),
			PrimitiveShapeType::PAYLOAD_TYPE_LINE => PrimitiveShapeLinePayload::read($in),
			PrimitiveShapeType::PAYLOAD_TYPE_CIRCLE_OR_SPHERE => PrimitiveShapeCircleOrSpherePayload::read($in),
			PrimitiveShapeType::PAYLOAD_TYPE_CYLINDER => PrimitiveShapeCylinderPayload::read($in),
			PrimitiveShapeType::PAYLOAD_TYPE_PYRAMID => PrimitiveShapePyramidPayload::read($in),
			PrimitiveShapeType::PAYLOAD_TYPE_ELLIPSOID => PrimitiveShapeEllipsoidPayload::read($in),
			PrimitiveShapeType::PAYLOAD_TYPE_CONE => PrimitiveShapeConePayload::read($in),
			default => throw new PacketDecodeException("Unknown payload type $payloadType")
		};

		return new self(
			$networkId,
			$shapeType,
			$location,
			$scale,
			$rotation,
			$totalTimeLeft,
			$maximumRenderDistance,
			$color,
			$dimensionId,
			$attachedToEntityId,
			$payload
		);
	}

	public function write(ByteBufferWriter $out) : void{
		VarInt::writeUnsignedLong($out, $this->networkId);
		CommonTypes::writeOptional($out, $this->type, fn(ByteBufferWriter $out, PrimitiveShapeType $type) => Byte::writeUnsigned($out, $type->value));
		CommonTypes::writeOptional($out, $this->location, CommonTypes::putVector3(...));
		CommonTypes::writeOptional($out, $this->scale, LE::writeFloat(...));
		CommonTypes::writeOptional($out, $this->rotation, CommonTypes::putVector3(...));
		CommonTypes::writeOptional($out, $this->totalTimeLeft, LE::writeFloat(...));
		CommonTypes::writeOptional($out, $this->maximumRenderDistance, LE::writeFloat(...));
		CommonTypes::writeOptional($out, $this->color, fn(ByteBufferWriter $out, Color $color) => LE::writeUnsignedInt($out, $color->toARGB()));
		CommonTypes::writeOptional($out, $this->dimensionId, fn(ByteBufferWriter $out, int $dimensionId) => VarInt::writeSignedInt($out, $dimensionId));
		CommonTypes::writeOptional($out, $this->attachedToEntityId, fn(ByteBufferWriter $out, int $entityId) => CommonTypes::putActorRuntimeId($out, $entityId));

		VarInt::writeUnsignedInt($out, $this->payload?->getTypeId() ?? PrimitiveShapeType::PAYLOAD_TYPE_NONE);
		$this->payload?->write($out);
	}
}
