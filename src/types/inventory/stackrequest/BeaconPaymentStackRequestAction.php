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

namespace pocketmine\network\mcpe\protocol\types\inventory\stackrequest;

use pmmp\encoding\ByteBufferReader;
use pmmp\encoding\ByteBufferWriter;
use pmmp\encoding\VarInt;
use pocketmine\network\mcpe\protocol\types\GetTypeIdFromConstTrait;

/**
 * Completes a transaction involving a beacon consuming input to produce effects.
 */
final class BeaconPaymentStackRequestAction extends ItemStackRequestAction{
	use GetTypeIdFromConstTrait;

	public const ID = ItemStackRequestActionType::BEACON_PAYMENT;

	public function __construct(
		private int $primaryEffectId,
		private int $secondaryEffectId
	){}

	public function getPrimaryEffectId() : int{ return $this->primaryEffectId; }

	public function getSecondaryEffectId() : int{ return $this->secondaryEffectId; }

	public static function read(ByteBufferReader $in) : self{
		$primary = VarInt::readSignedInt($in);
		$secondary = VarInt::readSignedInt($in);
		return new self($primary, $secondary);
	}

	public function write(ByteBufferWriter $out) : void{
		VarInt::writeSignedInt($out, $this->primaryEffectId);
		VarInt::writeSignedInt($out, $this->secondaryEffectId);
	}
}
