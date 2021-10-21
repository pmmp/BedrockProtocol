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

namespace pocketmine\network\mcpe\protocol;

#include <rules/DataPacket.h>

use pocketmine\network\mcpe\protocol\serializer\PacketSerializer;

class CommandBlockUpdatePacket extends DataPacket implements ServerboundPacket{
	public const NETWORK_ID = ProtocolInfo::COMMAND_BLOCK_UPDATE_PACKET;

	public bool $isBlock;

	public int $x;
	public int $y;
	public int $z;
	public int $commandBlockMode;
	public bool $isRedstoneMode;
	public bool $isConditional;

	public int $minecartEid;

	public string $command;
	public string $lastOutput;
	public string $name;
	public bool $shouldTrackOutput;
	public int $tickDelay;
	public bool $executeOnFirstTick;

	protected function decodePayload(PacketSerializer $in) : void{
		$this->isBlock = $in->getBool();

		if($this->isBlock){
			$in->getBlockPosition($this->x, $this->y, $this->z);
			$this->commandBlockMode = $in->getUnsignedVarInt();
			$this->isRedstoneMode = $in->getBool();
			$this->isConditional = $in->getBool();
		}else{
			//Minecart with command block
			$this->minecartEid = $in->getEntityRuntimeId();
		}

		$this->command = $in->getString();
		$this->lastOutput = $in->getString();
		$this->name = $in->getString();

		$this->shouldTrackOutput = $in->getBool();
		$this->tickDelay = $in->getLInt();
		$this->executeOnFirstTick = $in->getBool();
	}

	protected function encodePayload(PacketSerializer $out) : void{
		$out->putBool($this->isBlock);

		if($this->isBlock){
			$out->putBlockPosition($this->x, $this->y, $this->z);
			$out->putUnsignedVarInt($this->commandBlockMode);
			$out->putBool($this->isRedstoneMode);
			$out->putBool($this->isConditional);
		}else{
			$out->putEntityRuntimeId($this->minecartEid);
		}

		$out->putString($this->command);
		$out->putString($this->lastOutput);
		$out->putString($this->name);

		$out->putBool($this->shouldTrackOutput);
		$out->putLInt($this->tickDelay);
		$out->putBool($this->executeOnFirstTick);
	}

	public function handle(PacketHandlerInterface $handler) : bool{
		return $handler->handleCommandBlockUpdate($this);
	}
}
