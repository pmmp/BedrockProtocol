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

class CodeBuilderSourcePacket extends DataPacket implements ServerboundPacket{
	public const NETWORK_ID = ProtocolInfo::CODE_BUILDER_SOURCE_PACKET;

	private int $operation;
	private int $category;
	private int $codeStatus;

	/**
	 * @generate-create-func
	 */
	public static function create(int $operation, int $category, int $codeStatus) : self{
		$result = new self;
		$result->operation = $operation;
		$result->category = $category;
		$result->codeStatus = $codeStatus;
		return $result;
	}

	public function getOperation() : int{ return $this->operation; }

	public function getCategory() : int{ return $this->category; }

	public function getCodeStatus() : int{ return $this->codeStatus; }

	protected function decodePayload(ByteBufferReader $in) : void{
		$this->operation = Byte::readUnsigned($in);
		$this->category = Byte::readUnsigned($in);
		$this->codeStatus = Byte::readUnsigned($in);
	}

	protected function encodePayload(ByteBufferWriter $out) : void{
		Byte::writeUnsigned($out, $this->operation);
		Byte::writeUnsigned($out, $this->category);
		Byte::writeUnsigned($out, $this->codeStatus);
	}

	public function handle(PacketHandlerInterface $handler) : bool{
		return $handler->handleCodeBuilderSource($this);
	}
}
