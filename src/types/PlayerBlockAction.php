<?php

/*
 *
 *  ____            _        _   __  __ _                  __  __ ____
 * |  _ \ ___   ___| | _____| |_|  \/  (_)_ __   ___      |  \/  |  _ \
 * | |_) / _ \ / __| |/ / _ \ __| |\/| | | '_ \ / _ \_____| |\/| | |_) |
 * |  __/ (_) | (__|   <  __/ |_| |  | | | | | |  __/_____| |  | |  __/
 * |_|   \___/ \___|_|\_\___|\__|_|  |_|_|_| |_|\___|     |_|  |_|_|
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * @author PocketMine Team
 * @link http://www.pocketmine.net/
 *
 *
 */

declare(strict_types=1);

namespace pocketmine\network\mcpe\protocol\types;

use pocketmine\network\mcpe\protocol\serializer\PacketSerializer;

/** This is used for PlayerAuthInput packet when the flags include PERFORM_BLOCK_ACTIONS */
final class PlayerBlockAction{

	public int $actionType;
	public BlockPosition $blockPos;
	public int $face;

	public function read(PacketSerializer $in) : void{
		$this->actionType = $in->getVarInt();
		switch($this->actionType){
			case PlayerAction::ABORT_BREAK:
			case PlayerAction::START_BREAK:
			case PlayerAction::CRACK_BREAK:
			case PlayerAction::PREDICT_DESTROY_BLOCK:
			case PlayerAction::CONTINUE_DESTROY_BLOCK:
				$this->blockPos = $in->getBlockPosition();
				$this->face = $in->getVarInt();
				break;
		}
	}

	public function write(PacketSerializer $out) : void{
		$out->putVarInt($this->actionType);
		switch($this->actionType){
			case PlayerAction::ABORT_BREAK:
			case PlayerAction::START_BREAK:
			case PlayerAction::CRACK_BREAK:
			case PlayerAction::PREDICT_DESTROY_BLOCK:
			case PlayerAction::CONTINUE_DESTROY_BLOCK:
				$out->putBlockPosition($this->blockPos);
				$out->putVarInt($this->face);
				break;
		}
	}

}
