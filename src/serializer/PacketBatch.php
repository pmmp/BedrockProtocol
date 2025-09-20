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

use pmmp\encoding\ByteBufferReader;
use pmmp\encoding\ByteBufferWriter;
use pmmp\encoding\DataDecodeException;
use pmmp\encoding\VarInt;
use pocketmine\network\mcpe\protocol\Packet;
use pocketmine\network\mcpe\protocol\PacketDecodeException;
use pocketmine\network\mcpe\protocol\PacketPool;
use function strlen;

class PacketBatch{

	private function __construct(){
		//NOOP
	}

	/**
	 * @phpstan-return \Generator<int, string, void, void>
	 * @throws PacketDecodeException
	 */
	final public static function decodeRaw(ByteBufferReader $in) : \Generator{
		$c = 0;
		while($in->getUnreadLength() > 0){
			try{
				$length = VarInt::readUnsignedInt($in);
				$buffer = $in->readByteArray($length);
			}catch(DataDecodeException $e){
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
	final public static function encodeRaw(ByteBufferWriter $out, array $packets) : void{
		foreach($packets as $packet){
			VarInt::writeUnsignedInt($out, strlen($packet));
			$out->writeByteArray($packet);
		}
	}

	/**
	 * @phpstan-return \Generator<int, Packet, void, void>
	 * @throws PacketDecodeException
	 */
	final public static function decodePackets(ByteBufferReader $in, PacketPool $packetPool) : \Generator{
		$c = 0;
		foreach(self::decodeRaw($in) as $packetBuffer){
			$packet = $packetPool->getPacket($packetBuffer);
			if($packet !== null){
				try{
					//TODO: this could use a view with a start and end offset to avoid extra string allocations
					//currently ByteBufferReader doesn't support this
					$packet->decode(new ByteBufferReader($packetBuffer));
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
	final public static function encodePackets(ByteBufferWriter $out, array $packets) : void{
		foreach($packets as $packet){
			$serializer = new ByteBufferWriter();
			$packet->encode($serializer);
			//this may require a copy, so don't call it twice
			$packetBuffer = $serializer->getData();
			VarInt::writeUnsignedInt($out, strlen($packetBuffer));
			$out->writeByteArray($packetBuffer);
		}
	}
}
