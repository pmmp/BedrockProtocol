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

namespace pocketmine\network\mcpe\protocol\types\inventory;

use pocketmine\network\mcpe\protocol\PacketDecodeException;
use pocketmine\network\mcpe\protocol\serializer\PacketSerializer;
use pocketmine\utils\BinaryDataException;
use function count;

abstract class TransactionData{
	/** @var NetworkInventoryAction[] */
	protected array $actions = [];

	/**
	 * @return NetworkInventoryAction[]
	 */
	final public function getActions() : array{
		return $this->actions;
	}

	abstract public function getTypeId() : int;

	/**
	 * @throws BinaryDataException
	 * @throws PacketDecodeException
	 */
	final public function decode(PacketSerializer $stream) : void{
		$actionCount = $stream->getUnsignedVarInt();
		for($i = 0; $i < $actionCount; ++$i){
			$this->actions[] = (new NetworkInventoryAction())->read($stream);
		}
		$this->decodeData($stream);
	}

	/**
	 * @throws BinaryDataException
	 * @throws PacketDecodeException
	 */
	abstract protected function decodeData(PacketSerializer $stream) : void;

	final public function encode(PacketSerializer $stream) : void{
		$stream->putUnsignedVarInt(count($this->actions));
		foreach($this->actions as $action){
			$action->write($stream);
		}
		$this->encodeData($stream);
	}

	abstract protected function encodeData(PacketSerializer $stream) : void;
}
