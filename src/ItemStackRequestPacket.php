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
use pmmp\encoding\VarInt;
use pocketmine\network\mcpe\protocol\types\inventory\stackrequest\ItemStackRequest;
use function count;

class ItemStackRequestPacket extends DataPacket implements ServerboundPacket{
	public const NETWORK_ID = ProtocolInfo::ITEM_STACK_REQUEST_PACKET;

	/** @var ItemStackRequest[] */
	private array $requests;

	/**
	 * @generate-create-func
	 * @param ItemStackRequest[] $requests
	 */
	public static function create(array $requests) : self{
		$result = new self;
		$result->requests = $requests;
		return $result;
	}

	/** @return ItemStackRequest[] */
	public function getRequests() : array{ return $this->requests; }

	protected function decodePayload(ByteBufferReader $in) : void{
		$this->requests = [];
		for($i = 0, $len = VarInt::readUnsignedInt($in); $i < $len; ++$i){
			$this->requests[] = ItemStackRequest::read($in);
		}
	}

	protected function encodePayload(ByteBufferWriter $out) : void{
		VarInt::writeUnsignedInt($out, count($this->requests));
		foreach($this->requests as $request){
			$request->write($out);
		}
	}

	public function handle(PacketHandlerInterface $handler) : bool{
		return $handler->handleItemStackRequest($this);
	}
}
