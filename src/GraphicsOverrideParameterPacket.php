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
use pmmp\encoding\VarInt;
use pocketmine\network\mcpe\protocol\serializer\CommonTypes;
use pocketmine\network\mcpe\protocol\types\GraphicsOverrideParameterType;
use pocketmine\network\mcpe\protocol\types\ParameterKeyframeValue;
use function count;

class GraphicsOverrideParameterPacket extends DataPacket{
	public const NETWORK_ID = ProtocolInfo::GRAPHICS_OVERRIDE_PARAMETER_PACKET;

	/** @var ParameterKeyframeValue[] */
	private array $values = [];
	private string $biomeIdentifier;
	private GraphicsOverrideParameterType $parameterType;
	private bool $reset;

	/**
	 * @generate-create-func
	 * @param ParameterKeyframeValue[] $values
	 */
	public static function create(array $values, string $biomeIdentifier, GraphicsOverrideParameterType $parameterType, bool $reset) : self{
		$result = new self;
		$result->values = $values;
		$result->biomeIdentifier = $biomeIdentifier;
		$result->parameterType = $parameterType;
		$result->reset = $reset;
		return $result;
	}

	/**
	 * @return ParameterKeyframeValue[]
	 */
	public function getValues() : array{ return $this->values; }

	public function getBiomeIdentifier() : string{ return $this->biomeIdentifier; }

	public function getParameterType() : GraphicsOverrideParameterType{ return $this->parameterType; }

	public function isReset() : bool{ return $this->reset; }

	protected function decodePayload(ByteBufferReader $in) : void{
		$count = VarInt::readUnsignedInt($in);
		for($i = 0; $i < $count; ++$i){
			$this->values[] = ParameterKeyframeValue::read($in);
		}
		$this->biomeIdentifier = CommonTypes::getString($in);
		$this->parameterType = GraphicsOverrideParameterType::fromPacket(Byte::readUnsigned($in));
		$this->reset = CommonTypes::getBool($in);
	}

	protected function encodePayload(ByteBufferWriter $out) : void{
		VarInt::writeUnsignedInt($out, count($this->values));
		foreach($this->values as $value){
			$value->write($out);
		}
		CommonTypes::putString($out, $this->biomeIdentifier);
		Byte::writeUnsigned($out, $this->parameterType->value);
		CommonTypes::putBool($out, $this->reset);
	}

	public function handle(PacketHandlerInterface $handler) : bool{
		return $handler->handleGraphicsOverrideParameter($this);
	}
}
