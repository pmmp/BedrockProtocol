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

namespace pocketmine\network\mcpe\protocol\types;

use pocketmine\network\mcpe\protocol\serializer\PacketSerializer;

/** This is used for PlayerAuthInput packet when the flags include PERFORM_BLOCK_ACTIONS */
final class PlayerBlockAction{

	public const START_BREAK = 0;
	public const ABORT_BREAK = 1;
	public const STOP_BREAK = 2;
	public const CRACK_BREAK = 18;
	public const PREDICT_DESTROY = 26;
	public const CONTINUE = 27;

	public int $actionType;
	public BlockPosition $blockPos;
	public int $face;

	public function read(PacketSerializer $in) : void{
		$this->actionType = $in->getVarInt();
		if(match ($this->actionType) {
			PlayerBlockAction::ABORT_BREAK, PlayerBlockAction::START_BREAK, PlayerBlockAction::CRACK_BREAK, PlayerBlockAction::PREDICT_DESTROY, PlayerBlockAction::CONTINUE => true,
			default => false
		}){
			$this->blockPos = $in->getBlockPosition();
			$this->face = $in->getVarInt();
		}
	}

	public function write(PacketSerializer $out) : void{
		$out->putVarInt($this->actionType);
		if(match ($this->actionType) {
			PlayerBlockAction::ABORT_BREAK, PlayerBlockAction::START_BREAK, PlayerBlockAction::CRACK_BREAK, PlayerBlockAction::PREDICT_DESTROY, PlayerBlockAction::CONTINUE => true,
			default => false
		}){
			$out->putBlockPosition($this->blockPos);
			$out->putVarInt($this->face);
		}
	}

}