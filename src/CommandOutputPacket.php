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

use pmmp\encoding\Byte;
use pmmp\encoding\ByteBufferReader;
use pmmp\encoding\ByteBufferWriter;
use pmmp\encoding\DataDecodeException;
use pmmp\encoding\VarInt;
use pocketmine\network\mcpe\protocol\serializer\CommonTypes;
use pocketmine\network\mcpe\protocol\types\command\CommandOriginData;
use pocketmine\network\mcpe\protocol\types\command\CommandOutputMessage;
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

	protected function decodePayload(ByteBufferReader $in) : void{
		$this->originData = CommonTypes::getCommandOriginData($in);
		$this->outputType = Byte::readUnsigned($in);
		$this->successCount = VarInt::readUnsignedInt($in);

		for($i = 0, $size = VarInt::readUnsignedInt($in); $i < $size; ++$i){
			$this->messages[] = $this->getCommandMessage($in);
		}

		if($this->outputType === self::TYPE_DATA_SET){
			$this->unknownString = CommonTypes::getString($in);
		}
	}

	/**
	 * @throws DataDecodeException
	 */
	protected function getCommandMessage(ByteBufferReader $in) : CommandOutputMessage{
		$message = new CommandOutputMessage();

		$message->isInternal = CommonTypes::getBool($in);
		$message->messageId = CommonTypes::getString($in);

		for($i = 0, $size = VarInt::readUnsignedInt($in); $i < $size; ++$i){
			$message->parameters[] = CommonTypes::getString($in);
		}

		return $message;
	}

	protected function encodePayload(ByteBufferWriter $out) : void{
		CommonTypes::putCommandOriginData($out, $this->originData);
		Byte::writeUnsigned($out, $this->outputType);
		VarInt::writeUnsignedInt($out, $this->successCount);

		VarInt::writeUnsignedInt($out, count($this->messages));
		foreach($this->messages as $message){
			$this->putCommandMessage($message, $out);
		}

		if($this->outputType === self::TYPE_DATA_SET){
			CommonTypes::putString($out, $this->unknownString);
		}
	}

	protected function putCommandMessage(CommandOutputMessage $message, ByteBufferWriter $out) : void{
		CommonTypes::putBool($out, $message->isInternal);
		CommonTypes::putString($out, $message->messageId);

		VarInt::writeUnsignedInt($out, count($message->parameters));
		foreach($message->parameters as $parameter){
			CommonTypes::putString($out, $parameter);
		}
	}

	public function handle(PacketHandlerInterface $handler) : bool{
		return $handler->handleCommandOutput($this);
	}
}
