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
use pocketmine\utils\BinaryStream;
use function strlen;

class PacketBatch{

	private function __construct(){
		//NOOP
	}

	/**
	 * @phpstan-return \Generator<int, string, void, void>
	 * @throws PacketDecodeException
	 */
	final public static function decodeRaw(BinaryStream $stream) : \Generator{
		$c = 0;
		while(!$stream->feof()){
			try{
				$length = $stream->getUnsignedVarInt();
				$buffer = $stream->get($length);
			}catch(BinaryDataException $e){
				throw new PacketDecodeException("Error decoding packet $c in batch: " . $e->getMessage(), 0, $e);
			}
			yield $buffer;
			$c++;
		}
	}

	/**
	 * @param string[] $packets
	 * @phpstan-param list<string> $packets
	 */
	final public static function encodeRaw(BinaryStream $stream, array $packets) : void{
		foreach($packets as $packet){
			$stream->putUnsignedVarInt(strlen($packet));
			$stream->put($packet);
		}
	}

	/**
	 * @phpstan-return \Generator<int, Packet, void, void>
	 * @throws PacketDecodeException
	 */
	final public static function decodePackets(BinaryStream $stream, PacketPool $packetPool) : \Generator{
		$c = 0;
		foreach(self::decodeRaw($stream) as $packetBuffer){
			$packet = $packetPool->getPacket($packetBuffer);
			if($packet !== null){
				try{
					$packet->decode(PacketSerializer::decoder($packetBuffer, 0));
				}catch(PacketDecodeException $e){
					throw new PacketDecodeException("Error decoding packet $c in batch: " . $e->getMessage(), 0, $e);
				}
				yield $packet;
			}else{
				throw new PacketDecodeException("Unknown packet $c in batch");
			}
			$c++;
		}
	}

	/**
	 * @param Packet[]       $packets
	 * @phpstan-param list<Packet> $packets
	 */
	final public static function encodePackets(BinaryStream $stream, array $packets) : void{
		foreach($packets as $packet){
			$serializer = PacketSerializer::encoder();
			$packet->encode($serializer);
			$stream->putUnsignedVarInt(strlen($serializer->getBuffer()));
			$stream->put($serializer->getBuffer());
		}
	}
}
