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
use pmmp\encoding\DataDecodeException;
use pmmp\encoding\LE;
use pmmp\encoding\VarInt;
use pocketmine\network\mcpe\protocol\serializer\CommonTypes;
use pocketmine\network\mcpe\protocol\types\command\CommandOriginData;
use pocketmine\network\mcpe\protocol\types\command\CommandOutputMessage;
use function count;

class CommandOutputPacket extends DataPacket implements ClientboundPacket{
	public const NETWORK_ID = ProtocolInfo::COMMAND_OUTPUT_PACKET;

	public const TYPE_LAST = "lastoutput";
	public const TYPE_SILENT = "silent";
	public const TYPE_ALL = "alloutput";
	public const TYPE_DATA_SET = "dataset";

	public CommandOriginData $originData;
	public string $outputType;
	public int $successCount;
	/** @var CommandOutputMessage[] */
	public array $messages = [];
	public ?string $data;

	protected function decodePayload(ByteBufferReader $in) : void{
		$this->originData = CommonTypes::getCommandOriginData($in);
		$this->outputType = CommonTypes::getString($in);
		$this->successCount = LE::readUnsignedInt($in);

		for($i = 0, $size = VarInt::readUnsignedInt($in); $i < $size; ++$i){
			$this->messages[] = $this->getCommandMessage($in);
		}

		$this->data = CommonTypes::readOptional($in, CommonTypes::getString(...));
	}

	/**
	 * @throws DataDecodeException
	 */
	protected function getCommandMessage(ByteBufferReader $in) : CommandOutputMessage{
		$message = new CommandOutputMessage();

		$message->messageId = CommonTypes::getString($in);
		$message->isInternal = CommonTypes::getBool($in);

		for($i = 0, $size = VarInt::readUnsignedInt($in); $i < $size; ++$i){
			$message->parameters[] = CommonTypes::getString($in);
		}

		return $message;
	}

	protected function encodePayload(ByteBufferWriter $out) : void{
		CommonTypes::putCommandOriginData($out, $this->originData);
		CommonTypes::putString($out, $this->outputType);
		LE::writeUnsignedInt($out, $this->successCount);

		VarInt::writeUnsignedInt($out, count($this->messages));
		foreach($this->messages as $message){
			$this->putCommandMessage($message, $out);
		}

		CommonTypes::writeOptional($out, $this->data, CommonTypes::putString(...));
	}

	protected function putCommandMessage(CommandOutputMessage $message, ByteBufferWriter $out) : void{
		CommonTypes::putString($out, $message->messageId);
		CommonTypes::putBool($out, $message->isInternal);

		VarInt::writeUnsignedInt($out, count($message->parameters));
		foreach($message->parameters as $parameter){
			CommonTypes::putString($out, $parameter);
		}
	}

	public function handle(PacketHandlerInterface $handler) : bool{
		return $handler->handleCommandOutput($this);
	}
}
