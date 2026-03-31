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

namespace pocketmine\network\mcpe\protocol\types;

use pmmp\encoding\ByteBufferReader;
use pmmp\encoding\ByteBufferWriter;
use pmmp\encoding\LE;
use pocketmine\network\mcpe\protocol\serializer\CommonTypes;

/**
 * @see AttributeLayer&AttributesUpdateEnvironment
 */
final class AttributeEnvironment{

	public function __construct(
		private string $name,
		private ?AttributeValue $fromAttribute,
		private AttributeValue $attribute,
		private ?AttributeValue $toAttribute,
		private int $currentTransitionTicks,
		private int $totalTransitionTicks,
		private string $easeType
	){}

	public function getName() : string{ return $this->name; }

	public function getFromAttribute() : ?AttributeValue{ return $this->fromAttribute; }

	public function getAttribute() : AttributeValue{ return $this->attribute; }

	public function getToAttribute() : ?AttributeValue{ return $this->toAttribute; }

	public function getCurrentTransitionTicks() : int{ return $this->currentTransitionTicks; }

	public function getTotalTransitionTicks() : int{ return $this->totalTransitionTicks; }

	/**
	 * @see CameraSetInstructionEaseType
	 */
	public function getEaseType() : string{ return $this->easeType; }

	public static function read(ByteBufferReader $in) : self{
		$name = CommonTypes::getString($in);
		$fromAttribute = CommonTypes::readOptional($in, fn() => AttributeValue::read($in));
		$attribute = AttributeValue::read($in);
		$toAttribute = CommonTypes::readOptional($in, fn() => AttributeValue::read($in));
		$currentTransitionTicks = LE::readUnsignedInt($in);
		$totalTransitionTicks = LE::readUnsignedInt($in);
		$easeType = CommonTypes::getString($in);

		return new self(
			$name,
			$fromAttribute,
			$attribute,
			$toAttribute,
			$currentTransitionTicks,
			$totalTransitionTicks,
			$easeType
		);
	}

	public function write(ByteBufferWriter $out) : void{
		CommonTypes::putString($out, $this->name);
		CommonTypes::writeOptional($out, $this->fromAttribute, fn(ByteBufferWriter $out, AttributeValue $value) => $value->write($out));
		$this->attribute->write($out);
		CommonTypes::writeOptional($out, $this->toAttribute, fn(ByteBufferWriter $out, AttributeValue $value) => $value->write($out));
		LE::writeUnsignedInt($out, $this->currentTransitionTicks);
		LE::writeUnsignedInt($out, $this->totalTransitionTicks);
		CommonTypes::putString($out, $this->easeType);
	}
}
