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

namespace pocketmine\network\mcpe\protocol\serializer;

use pocketmine\network\mcpe\protocol\Packet;
use pocketmine\network\mcpe\protocol\PacketDecodeException;
use pocketmine\network\mcpe\protocol\PacketPool;
use pocketmine\utils\BinaryDataException;

class PacketBatch{
	public function __construct(
		private string $buffer
	){}

	/**
	 * @return \Generator|Packet[]|null[]
	 * @phpstan-return \Generator<int, array{?Packet, string}, void, void>
	 * @throws PacketDecodeException
	 */
	public function getPackets(PacketPool $packetPool, PacketSerializerContext $decoderContext, int $max) : \Generator{
		$serializer = PacketSerializer::decoder($this->buffer, 0, $decoderContext);
		for($c = 0; $c < $max and !$serializer->feof(); ++$c){
			try{
				$buffer = $serializer->getString();
				yield $c => [$packetPool->getPacket($buffer), $buffer];
			}catch(BinaryDataException $e){
				throw new PacketDecodeException("Error decoding packet $c of batch: " . $e->getMessage(), 0, $e);
			}
		}
		if(!$serializer->feof()){
			throw new PacketDecodeException("Reached limit of $max packets in a single batch");
		}
	}

	/**
	 * Constructs a packet batch from the given list of packets.
	 *
	 * @return PacketBatch
	 */
	public static function fromPackets(PacketSerializerContext $context, Packet ...$packets) : self{
		$serializer = PacketSerializer::encoder($context);
		foreach($packets as $packet){
			$subSerializer = PacketSerializer::encoder($context);
			$packet->encode($subSerializer);
			$serializer->putString($subSerializer->getBuffer());
		}
		return new self($serializer->getBuffer());
	}

	public function getBuffer() : string{
		return $this->buffer;
	}
}
