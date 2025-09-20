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
use pmmp\encoding\LE;
use pocketmine\network\mcpe\protocol\serializer\CommonTypes;
use pocketmine\network\mcpe\protocol\types\CompressionAlgorithm;

/**
 * This is the first packet sent by the server in a game session, in response to a network settings request (only if
 * protocol versions are a match). It includes values for things like which compression algorithm to use, size threshold
 * for compressing packets, and more.
 */
class NetworkSettingsPacket extends DataPacket implements ClientboundPacket{
	public const NETWORK_ID = ProtocolInfo::NETWORK_SETTINGS_PACKET;

	public const COMPRESS_NOTHING = 0;
	public const COMPRESS_EVERYTHING = 1;

	private int $compressionThreshold;
	private int $compressionAlgorithm;
	private bool $enableClientThrottling;
	private int $clientThrottleThreshold;
	private float $clientThrottleScalar;

	/**
	 * @generate-create-func
	 */
	public static function create(int $compressionThreshold, int $compressionAlgorithm, bool $enableClientThrottling, int $clientThrottleThreshold, float $clientThrottleScalar) : self{
		$result = new self;
		$result->compressionThreshold = $compressionThreshold;
		$result->compressionAlgorithm = $compressionAlgorithm;
		$result->enableClientThrottling = $enableClientThrottling;
		$result->clientThrottleThreshold = $clientThrottleThreshold;
		$result->clientThrottleScalar = $clientThrottleScalar;
		return $result;
	}

	public function canBeSentBeforeLogin() : bool{
		return true;
	}

	public function getCompressionThreshold() : int{
		return $this->compressionThreshold;
	}

	/**
	 * @see CompressionAlgorithm
	 */
	public function getCompressionAlgorithm() : int{ return $this->compressionAlgorithm; }

	public function isEnableClientThrottling() : bool{ return $this->enableClientThrottling; }

	public function getClientThrottleThreshold() : int{ return $this->clientThrottleThreshold; }

	public function getClientThrottleScalar() : float{ return $this->clientThrottleScalar; }

	protected function decodePayload(ByteBufferReader $in) : void{
		$this->compressionThreshold = LE::readUnsignedShort($in);
		$this->compressionAlgorithm = LE::readUnsignedShort($in);
		$this->enableClientThrottling = CommonTypes::getBool($in);
		$this->clientThrottleThreshold = Byte::readUnsigned($in);
		$this->clientThrottleScalar = LE::readFloat($in);
	}

	protected function encodePayload(ByteBufferWriter $out) : void{
		LE::writeUnsignedShort($out, $this->compressionThreshold);
		LE::writeUnsignedShort($out, $this->compressionAlgorithm);
		CommonTypes::putBool($out, $this->enableClientThrottling);
		Byte::writeUnsigned($out, $this->clientThrottleThreshold);
		LE::writeFloat($out, $this->clientThrottleScalar);
	}

	public function handle(PacketHandlerInterface $handler) : bool{
		return $handler->handleNetworkSettings($this);
	}
}
