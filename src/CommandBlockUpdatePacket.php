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

namespace pocketmine\network\mcpe\protocol;

use pocketmine\network\mcpe\protocol\serializer\PacketSerializer;
use pocketmine\network\mcpe\protocol\types\BlockPosition;

class CommandBlockUpdatePacket extends DataPacket implements ServerboundPacket{
	public const NETWORK_ID = ProtocolInfo::COMMAND_BLOCK_UPDATE_PACKET;

	public bool $isBlock;

	public BlockPosition $blockPosition;
	public int $commandBlockMode;
	public bool $isRedstoneMode;
	public bool $isConditional;

	public int $minecartActorRuntimeId;

	public string $command;
	public string $lastOutput;
	public string $name;
	public bool $shouldTrackOutput;
	public int $tickDelay;
	public bool $executeOnFirstTick;

	protected function decodePayload(PacketSerializer $in) : void{
		$this->isBlock = $in->getBool();

		if($this->isBlock){
			$this->blockPosition = $in->getBlockPosition();
			$this->commandBlockMode = $in->getUnsignedVarInt();
			$this->isRedstoneMode = $in->getBool();
			$this->isConditional = $in->getBool();
		}else{
			//Minecart with command block
			$this->minecartActorRuntimeId = $in->getActorRuntimeId();
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
			$out->putBlockPosition($this->blockPosition);
			$out->putUnsignedVarInt($this->commandBlockMode);
			$out->putBool($this->isRedstoneMode);
			$out->putBool($this->isConditional);
		}else{
			$out->putActorRuntimeId($this->minecartActorRuntimeId);
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
