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

namespace pocketmine\network\mcpe\protocol\types\camera;

use function array_flip;

final class CameraSetInstructionEaseType{

	private function __construct(){
		//NOOP
	}

	public const LINEAR = 0;
	public const SPRING = 1;
	public const IN_QUAD = 2;
	public const OUT_QUAD = 3;
	public const IN_OUT_QUAD = 4;
	public const IN_CUBIC = 5;
	public const OUT_CUBIC = 6;
	public const IN_OUT_CUBIC = 7;
	public const IN_QUART = 8;
	public const OUT_QUART = 9;
	public const IN_OUT_QUART = 10;
	public const IN_QUINT = 11;
	public const OUT_QUINT = 12;
	public const IN_OUT_QUINT = 13;
	public const IN_SINE = 14;
	public const OUT_SINE = 15;
	public const IN_OUT_SINE = 16;
	public const IN_EXPO = 17;
	public const OUT_EXPO = 18;
	public const IN_OUT_EXPO = 19;
	public const IN_CIRC = 20;
	public const OUT_CIRC = 21;
	public const IN_OUT_CIRC = 22;
	public const IN_BOUNCE = 23;
	public const OUT_BOUNCE = 24;
	public const IN_OUT_BOUNCE = 25;
	public const IN_BACK = 26;
	public const OUT_BACK = 27;
	public const IN_OUT_BACK = 28;
	public const IN_ELASTIC = 29;
	public const OUT_ELASTIC = 30;
	public const IN_OUT_ELASTIC = 31;

	private const EASE_TYPE_NAMES = [
		self::LINEAR => 'linear',
		self::SPRING => 'spring',
		self::IN_SINE => 'in_sine',
		self::OUT_SINE => 'out_sine',
		self::IN_OUT_SINE => 'in_out_sine',
		self::IN_QUAD => 'in_quad',
		self::OUT_QUAD => 'out_quad',
		self::IN_OUT_QUAD => 'in_out_quad',
		self::IN_CUBIC => 'in_cubic',
		self::OUT_CUBIC => 'out_cubic',
		self::IN_OUT_CUBIC => 'in_out_cubic',
		self::IN_QUART => 'in_quart',
		self::OUT_QUART => 'out_quart',
		self::IN_OUT_QUART => 'in_out_quart',
		self::IN_QUINT => 'in_quint',
		self::OUT_QUINT => 'out_quint',
		self::IN_OUT_QUINT => 'in_out_quint',
		self::IN_EXPO => 'in_expo',
		self::OUT_EXPO => 'out_expo',
		self::IN_OUT_EXPO => 'in_out_expo',
		self::IN_CIRC => 'in_circ',
		self::OUT_CIRC => 'out_circ',
		self::IN_OUT_CIRC => 'in_out_circ',
		self::IN_BACK => 'in_back',
		self::OUT_BACK => 'out_back',
		self::IN_OUT_BACK => 'in_out_back',
		self::IN_ELASTIC => 'in_elastic',
		self::OUT_ELASTIC => 'out_elastic',
		self::IN_OUT_ELASTIC => 'in_out_elastic',
		self::IN_BOUNCE => 'in_bounce',
		self::OUT_BOUNCE => 'out_bounce',
		self::IN_OUT_BOUNCE => 'in_out_bounce'
	];

	public static function toName(int $value) : string{
		return self::EASE_TYPE_NAMES[$value] ?? throw new \InvalidArgumentException("Invalid raw value \"$value\" for EaseType.");
	}

	public static function fromName(string $name) : int{
		static $cache = null;
		if($cache === null){
			$cache = array_flip(self::EASE_TYPE_NAMES);
		}

		return $cache[$name] ?? throw new \InvalidArgumentException("Invalid raw value \"$name\" for EaseType.");
	}
}
