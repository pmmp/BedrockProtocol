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

namespace pocketmine\network\mcpe\protocol\types\ddui;

use pmmp\encoding\ByteBufferReader;
use pmmp\encoding\ByteBufferWriter;
use pmmp\encoding\LE;
use pmmp\encoding\VarInt;
use pocketmine\network\mcpe\protocol\serializer\CommonTypes;
use pocketmine\network\mcpe\protocol\types\cereal\DynamicValue;
use pocketmine\network\mcpe\protocol\types\cereal\DynamicValueType;
use pocketmine\network\mcpe\protocol\types\GetTypeIdFromConstTrait;

/**
 * @see ClientboundDataStorePacket
 */
final class DataStoreChange implements DataStoreOperation{
	use GetTypeIdFromConstTrait;

	public const ID = DataStoreOperationType::CHANGE;

	public function __construct(
		private string $name,
		private string $property,
		private int $updateCount,
		private ?DynamicValue $data
	){}

	public function getName() : string{ return $this->name; }

	public function getProperty() : string{ return $this->property; }

	public function getUpdateCount() : int{ return $this->updateCount; }

	public function getData() : ?DynamicValue{ return $this->data; }

	public static function read(ByteBufferReader $in) : self{
		$name = CommonTypes::getString($in);
		$property = CommonTypes::getString($in);
		$updateCount = VarInt::readUnsignedInt($in);

		$type = LE::readUnsignedInt($in);
		$data = DynamicValue::read($in, $type);

		return new self(
			$name,
			$property,
			$updateCount,
			$data,
		);
	}

	public function write(ByteBufferWriter $out) : void{
		CommonTypes::putString($out, $this->name);
		CommonTypes::putString($out, $this->property);
		VarInt::writeUnsignedInt($out, $this->updateCount);

		//TODO: yucky, we really need to revamp how unions are handled :(
		$type = $this->data?->getTypeId() ?? DynamicValueType::NULL;
		LE::writeUnsignedInt($out, $type);
		$this->data?->write($out);
	}
}
