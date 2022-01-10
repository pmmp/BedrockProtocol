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
use pocketmine\network\mcpe\protocol\types\command\CommandOriginData;
use pocketmine\network\mcpe\protocol\types\command\CommandOutputMessage;
use pocketmine\utils\BinaryDataException;
use function count;

class CommandOutputPacket extends DataPacket implements ClientboundPacket{
	public const NETWORK_ID = ProtocolInfo::COMMAND_OUTPUT_PACKET;

	public const TYPE_LAST = 1;
	public const TYPE_SILENT = 2;
	public const TYPE_ALL = 3;
	public const TYPE_DATA_SET = 4;

	public CommandOriginData $originData;
	public int $outputType;
	public int $successCount;
	/** @var CommandOutputMessage[] */
	public array $messages = [];
	public string $unknownString;

	protected function decodePayload(PacketSerializer $in) : void{
		$this->originData = $in->getCommandOriginData();
		$this->outputType = $in->getByte();
		$this->successCount = $in->getUnsignedVarInt();

		for($i = 0, $size = $in->getUnsignedVarInt(); $i < $size; ++$i){
			$this->messages[] = $this->getCommandMessage($in);
		}

		if($this->outputType === self::TYPE_DATA_SET){
			$this->unknownString = $in->getString();
		}
	}

	/**
	 * @throws BinaryDataException
	 */
	protected function getCommandMessage(PacketSerializer $in) : CommandOutputMessage{
		$message = new CommandOutputMessage();

		$message->isInternal = $in->getBool();
		$message->messageId = $in->getString();

		for($i = 0, $size = $in->getUnsignedVarInt(); $i < $size; ++$i){
			$message->parameters[] = $in->getString();
		}

		return $message;
	}

	protected function encodePayload(PacketSerializer $out) : void{
		$out->putCommandOriginData($this->originData);
		$out->putByte($this->outputType);
		$out->putUnsignedVarInt($this->successCount);

		$out->putUnsignedVarInt(count($this->messages));
		foreach($this->messages as $message){
			$this->putCommandMessage($message, $out);
		}

		if($this->outputType === self::TYPE_DATA_SET){
			$out->putString($this->unknownString);
		}
	}

	protected function putCommandMessage(CommandOutputMessage $message, PacketSerializer $out) : void{
		$out->putBool($message->isInternal);
		$out->putString($message->messageId);

		$out->putUnsignedVarInt(count($message->parameters));
		foreach($message->parameters as $parameter){
			$out->putString($parameter);
		}
	}

	public function handle(PacketHandlerInterface $handler) : bool{
		return $handler->handleCommandOutput($this);
	}
}
