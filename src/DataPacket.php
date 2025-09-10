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
use pmmp\encoding\VarInt;
use function get_class;

abstract class DataPacket implements Packet{

	public const NETWORK_ID = 0;

	public const PID_MASK = 0x3ff; //10 bits

	private const SUBCLIENT_ID_MASK = 0x03; //2 bits
	private const SENDER_SUBCLIENT_ID_SHIFT = 10;
	private const RECIPIENT_SUBCLIENT_ID_SHIFT = 12;

	public int $senderSubId = 0;
	public int $recipientSubId = 0;

	public function pid() : int{
		return $this::NETWORK_ID;
	}

	public function getName() : string{
		return (new \ReflectionClass($this))->getShortName();
	}

	public function canBeSentBeforeLogin() : bool{
		return false;
	}

	/**
	 * @throws PacketDecodeException
	 */
	final public function decode(ByteBufferReader $in) : void{
		try{
			$this->decodeHeader($in);
			$this->decodePayload($in);
		}catch(DataDecodeException | PacketDecodeException $e){
			throw PacketDecodeException::wrap($e, $this->getName());
		}
	}

	/**
	 * @throws DataDecodeException
	 * @throws PacketDecodeException
	 */
	protected function decodeHeader(ByteBufferReader $in) : void{
		$header = VarInt::readUnsignedInt($in);
		$pid = $header & self::PID_MASK;
		if($pid !== static::NETWORK_ID){
			//TODO: this means a logical error in the code, but how to prevent it from happening?
			throw new PacketDecodeException("Expected " . static::NETWORK_ID . " for packet ID, got $pid");
		}
		$this->senderSubId = ($header >> self::SENDER_SUBCLIENT_ID_SHIFT) & self::SUBCLIENT_ID_MASK;
		$this->recipientSubId = ($header >> self::RECIPIENT_SUBCLIENT_ID_SHIFT) & self::SUBCLIENT_ID_MASK;

	}

	/**
	 * Decodes the packet body, without the packet ID or other generic header fields.
	 *
	 * @throws PacketDecodeException
	 * @throws DataDecodeException
	 */
	abstract protected function decodePayload(ByteBufferReader $in) : void;

	final public function encode(ByteBufferWriter $out) : void{
		$this->encodeHeader($out);
		$this->encodePayload($out);
	}

	protected function encodeHeader(ByteBufferWriter $out) : void{
		VarInt::writeUnsignedInt($out,
			static::NETWORK_ID |
			($this->senderSubId << self::SENDER_SUBCLIENT_ID_SHIFT) |
			($this->recipientSubId << self::RECIPIENT_SUBCLIENT_ID_SHIFT)
		);
	}

	/**
	 * Encodes the packet body, without the packet ID or other generic header fields.
	 */
	abstract protected function encodePayload(ByteBufferWriter $out) : void;

	/**
	 * @param string $name
	 *
	 * @return mixed
	 */
	public function __get($name){
		throw new \Error("Undefined property: " . get_class($this) . "::\$" . $name);
	}

	/**
	 * @param string $name
	 * @param mixed  $value
	 */
	public function __set($name, $value) : void{
		throw new \Error("Undefined property: " . get_class($this) . "::\$" . $name);
	}
}
