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
use pocketmine\network\mcpe\protocol\serializer\CommonTypes;

class GameTestResultsPacket extends DataPacket implements ClientboundPacket{
	public const NETWORK_ID = ProtocolInfo::GAME_TEST_RESULTS_PACKET;

	private bool $success;
	private string $error;
	private string $testName;

	/**
	 * @generate-create-func
	 */
	public static function create(bool $success, string $error, string $testName) : self{
		$result = new self;
		$result->success = $success;
		$result->error = $error;
		$result->testName = $testName;
		return $result;
	}

	public function isSuccess() : bool{ return $this->success; }

	public function getError() : string{ return $this->error; }

	public function getTestName() : string{ return $this->testName; }

	protected function decodePayload(ByteBufferReader $in) : void{
		$this->success = CommonTypes::getBool($in);
		$this->error = CommonTypes::getString($in);
		$this->testName = CommonTypes::getString($in);
	}

	protected function encodePayload(ByteBufferWriter $out) : void{
		CommonTypes::putBool($out, $this->success);
		CommonTypes::putString($out, $this->error);
		CommonTypes::putString($out, $this->testName);
	}

	public function handle(PacketHandlerInterface $handler) : bool{
		return $handler->handleGameTestResults($this);
	}
}
