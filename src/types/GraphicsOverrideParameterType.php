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

enum GraphicsOverrideParameterType : int{
	use PacketIntEnumTrait;

	case SKY_ZENITH_COLOR = 0;
	case SKY_HORIZON_COLOR = 1;
	case HORIZON_BLEND_MIN = 2;
	case HORIZON_BLEND_MAX = 3;
	case HORIZON_BLEND_START = 4;
	case HORIZON_BLEND_MIE_START = 5;
	case RAYLEIGH_STRENGTH = 6;
	case SUN_MIE_STRENGTH = 7;
	case MOON_MIE_STRENGTH = 8;
	case SUN_GLARE_SHAPE = 9;
	case CHLOROPHYLL = 10;
	case CDOM = 11;
	case SUSPENDED_SEDIMENT = 12;
	case WAVES_DEPTH = 13;
	case WAVES_FREQUENCY = 14;
	case WAVES_FREQUENCY_SCALING = 15;
	case WAVES_SPEED = 16;
	case WAVES_SPEED_SCALING = 17;
	case WAVES_SHAPE = 18;
	case WAVES_OCTAVES = 19;
	case WAVES_MIX = 20;
	case WAVES_PULL = 21;
	case WAVES_DIRECTION_INCREMENT = 22;
	case MIDTONES_CONTRAST = 23;
	case HIGHLIGHTS_CONTRAST = 24;
	case SHADOWS_CONTRAST = 25;
	case HIGHLIGHTS_GAIN = 26;
	case HIGHLIGHTS_GAMMA = 27;
	case HIGHLIGHTS_OFFSET = 28;
	case HIGHLIGHTS_SATURATION = 29;
	case MIDTONES_GAIN = 30;
	case MIDTONES_GAMMA = 31;
	case MIDTONES_OFFSET = 32;
	case MIDTONES_SATURATION = 33;
	case SHADOWS_GAIN = 34;
	case SHADOWS_GAMMA = 35;
	case SHADOWS_OFFSET = 36;
	case SHADOWS_SATURATION = 37;
	case HIGHLIGHTS_MIN = 38;
	case SHADOWS_MAX = 39;
	case TEMPERATURE = 40;
	case SUN_COLOR = 41;
	case SUN_ILLUMINANCE = 42;
	case MOON_COLOR = 43;
	case MOON_ILLUMINANCE = 44;
	case FLASH_COLOR = 45;
	case FLASH_ILLUMINANCE = 46;
	case AMBIENT_COLOR = 47;
	case AMBIENT_ILLUMINANCE = 48;
}
