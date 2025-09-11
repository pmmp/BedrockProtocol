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
use pocketmine\network\mcpe\protocol\types\inventory\FullContainerName;
use function count;

class ContainerRegistryCleanupPacket extends DataPacket implements ClientboundPacket{
	public const NETWORK_ID = ProtocolInfo::CONTAINER_REGISTRY_CLEANUP_PACKET;

	/** @var FullContainerName[] */
	private array $removedContainers;

	/**
	 * @generate-create-func
	 * @param FullContainerName[] $removedContainers
	 */
	public static function create(array $removedContainers) : self{
		$result = new self;
		$result->removedContainers = $removedContainers;
		return $result;
	}

	/**
	 * @return FullContainerName[]
	 */
	public function getRemovedContainers() : array{ return $this->removedContainers; }

	protected function decodePayload(ByteBufferReader $in) : void{
		$this->removedContainers = [];
		for($i = 0, $len = VarInt::readUnsignedInt($in); $i < $len; ++$i){
			$this->removedContainers[] = FullContainerName::read($in);
		}
	}

	protected function encodePayload(ByteBufferWriter $out) : void{
		VarInt::writeUnsignedInt($out, count($this->removedContainers));
		foreach($this->removedContainers as $container){
			$container->write($out);
		}
	}

	public function handle(PacketHandlerInterface $handler) : bool{
		return $handler->handleContainerRegistryCleanup($this);
	}
}
