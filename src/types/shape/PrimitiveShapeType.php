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

use pocketmine\network\mcpe\protocol\types\PacketIntEnumTrait;

enum PrimitiveShapeType : int{
	use PacketIntEnumTrait;

	case LINE = 0;
	case BOX = 1;
	case SPHERE = 2;
	case CIRCLE = 3;
	case TEXT = 4;
	case ARROW = 5;
	case CYLINDER = 6;
	case PYRAMID = 7;
	case ELLIPSOID = 8;
	case CONE = 9;

	public const PAYLOAD_TYPE_NONE = 0;
	public const PAYLOAD_TYPE_ARROW = 1;
	public const PAYLOAD_TYPE_TEXT = 2;
	public const PAYLOAD_TYPE_BOX = 3;
	public const PAYLOAD_TYPE_LINE = 4;
	public const PAYLOAD_TYPE_CIRCLE_OR_SPHERE = 5;
	public const PAYLOAD_TYPE_CYLINDER = 6;
	public const PAYLOAD_TYPE_PYRAMID = 7;
	public const PAYLOAD_TYPE_ELLIPSOID = 8;
	public const PAYLOAD_TYPE_CONE = 9;

	/**
	 * UGH
	 */
	public function getPayloadType() : int{
		return match($this){
			self::ARROW => self::PAYLOAD_TYPE_ARROW,
			self::TEXT => self::PAYLOAD_TYPE_TEXT,
			self::BOX => self::PAYLOAD_TYPE_BOX,
			self::LINE => self::PAYLOAD_TYPE_LINE,
			self::CIRCLE, self::SPHERE => self::PAYLOAD_TYPE_CIRCLE_OR_SPHERE,
			self::CYLINDER => self::PAYLOAD_TYPE_CYLINDER,
			self::PYRAMID => self::PAYLOAD_TYPE_PYRAMID,
			self::ELLIPSOID => self::PAYLOAD_TYPE_ELLIPSOID,
			self::CONE => self::PAYLOAD_TYPE_CONE,
		};
	}
}
