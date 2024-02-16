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

namespace pocketmine\network\mcpe\protocol\types\inventory\stackresponse;

use pocketmine\network\mcpe\protocol\serializer\PacketSerializer;
use function count;

final class ItemStackResponse{

	public const RESULT_OK = 0;
	public const RESULT_ERROR = 1;
	//TODO: there are a ton more possible result types but we don't need them yet and they are wayyyyyy too many for me
	//to waste my time on right now...

	/**
	 * @param ItemStackResponseContainerInfo[] $containerInfos
	 */
	public function __construct(
		private int $result,
		private int $requestId,
		private array $containerInfos = []
	){
		if($this->result !== self::RESULT_OK && count($this->containerInfos) !== 0){
			throw new \InvalidArgumentException("Container infos must be empty if rejecting the request");
		}
	}

	public function getResult() : int{ return $this->result; }

	public function getRequestId() : int{ return $this->requestId; }

	/** @return ItemStackResponseContainerInfo[] */
	public function getContainerInfos() : array{ return $this->containerInfos; }

	public static function read(PacketSerializer $in) : self{
		$result = $in->getByte();
		$requestId = $in->readItemStackRequestId();
		$containerInfos = [];
		if($result === self::RESULT_OK){
			for($i = 0, $len = $in->getUnsignedVarInt(); $i < $len; ++$i){
				$containerInfos[] = ItemStackResponseContainerInfo::read($in);
			}
		}
		return new self($result, $requestId, $containerInfos);
	}

	public function write(PacketSerializer $out) : void{
		$out->putByte($this->result);
		$out->writeItemStackRequestId($this->requestId);
		if($this->result === self::RESULT_OK){
			$out->putUnsignedVarInt(count($this->containerInfos));
			foreach($this->containerInfos as $containerInfo){
				$containerInfo->write($out);
			}
		}
	}
}
