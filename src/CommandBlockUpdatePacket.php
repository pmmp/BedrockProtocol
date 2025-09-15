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

use pmmp\encoding\ByteBufferReader;
use pmmp\encoding\ByteBufferWriter;
use pmmp\encoding\LE;
use pmmp\encoding\VarInt;
use pocketmine\network\mcpe\protocol\serializer\CommonTypes;
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
	public string $filteredName;
	public bool $shouldTrackOutput;
	public int $tickDelay;
	public bool $executeOnFirstTick;

	protected function decodePayload(ByteBufferReader $in) : void{
		$this->isBlock = CommonTypes::getBool($in);

		if($this->isBlock){
			$this->blockPosition = CommonTypes::getBlockPosition($in);
			$this->commandBlockMode = VarInt::readUnsignedInt($in);
			$this->isRedstoneMode = CommonTypes::getBool($in);
			$this->isConditional = CommonTypes::getBool($in);
		}else{
			//Minecart with command block
			$this->minecartActorRuntimeId = CommonTypes::getActorRuntimeId($in);
		}

		$this->command = CommonTypes::getString($in);
		$this->lastOutput = CommonTypes::getString($in);
		$this->name = CommonTypes::getString($in);
		$this->filteredName = CommonTypes::getString($in);
		$this->shouldTrackOutput = CommonTypes::getBool($in);
		$this->tickDelay = LE::readUnsignedInt($in);
		$this->executeOnFirstTick = CommonTypes::getBool($in);
	}

	protected function encodePayload(ByteBufferWriter $out) : void{
		CommonTypes::putBool($out, $this->isBlock);

		if($this->isBlock){
			CommonTypes::putBlockPosition($out, $this->blockPosition);
			VarInt::writeUnsignedInt($out, $this->commandBlockMode);
			CommonTypes::putBool($out, $this->isRedstoneMode);
			CommonTypes::putBool($out, $this->isConditional);
		}else{
			CommonTypes::putActorRuntimeId($out, $this->minecartActorRuntimeId);
		}

		CommonTypes::putString($out, $this->command);
		CommonTypes::putString($out, $this->lastOutput);
		CommonTypes::putString($out, $this->name);
		CommonTypes::putString($out, $this->filteredName);
		CommonTypes::putBool($out, $this->shouldTrackOutput);
		LE::writeUnsignedInt($out, $this->tickDelay);
		CommonTypes::putBool($out, $this->executeOnFirstTick);
	}

	public function handle(PacketHandlerInterface $handler) : bool{
		return $handler->handleCommandBlockUpdate($this);
	}
}
