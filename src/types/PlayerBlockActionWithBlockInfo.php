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

use pocketmine\network\mcpe\protocol\serializer\PacketSerializer;

/** This is used for PlayerAuthInput packet when the flags include PERFORM_BLOCK_ACTIONS */
final class PlayerBlockActionWithBlockInfo implements PlayerBlockAction{
	public function __construct(
		private int $actionType,
		private BlockPosition $blockPosition,
		private int $face
	){
		if(!self::isValidActionType($actionType)){
			throw new \InvalidArgumentException("Invalid action type for " . self::class);
		}
	}

	public function getActionType() : int{ return $this->actionType; }

	public function getBlockPosition() : BlockPosition{ return $this->blockPosition; }

	public function getFace() : int{ return $this->face; }

	public static function read(PacketSerializer $in, int $actionType) : self{
		$blockPosition = $in->getSignedBlockPosition();
		$face = $in->getVarInt();
		return new self($actionType, $blockPosition, $face);
	}

	public function write(PacketSerializer $out) : void{
		$out->putSignedBlockPosition($this->blockPosition);
		$out->putVarInt($this->face);
	}

	public static function isValidActionType(int $actionType) : bool{
		return match($actionType){
			PlayerAction::ABORT_BREAK,
			PlayerAction::START_BREAK,
			PlayerAction::CRACK_BREAK,
			PlayerAction::PREDICT_DESTROY_BLOCK,
			PlayerAction::CONTINUE_DESTROY_BLOCK => true,
			default => false
		};
	}
}
