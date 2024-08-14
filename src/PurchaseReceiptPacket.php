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

use pocketmine\network\mcpe\JwtUtils;
use pocketmine\network\mcpe\protocol\serializer\PacketSerializer;
use pocketmine\network\mcpe\protocol\types\login\JwtChain;
use function count;

class PurchaseReceiptPacket extends DataPacket implements ServerboundPacket{
	public const NETWORK_ID = ProtocolInfo::PURCHASE_RECEIPT_PACKET;

	/** @var string[] */
	public array $entries = [];

	/**
	 * @generate-create-func
	 * @param string[] $entries
	 */
	public static function create(array $entries) : self{
		$result = new self;
		$result->entries = $entries;
		return $result;
	}

	protected function decodePayload(PacketSerializer $in) : void{
		$count = $in->getUnsignedVarInt();
		if($count > 1) {
			throw new PacketDecodeException("There should be a maximum of one jwt");
		}

		for($i = 0; $i < $count; ++$i) {
			[, $data, ] = JwtUtils::parse($in->getString());
			if(!isset($data["sub"])) {
				throw new PacketDecodeException("JWT payload must contain 'sub'");
			}
			if(!isset($data["tid"])) {
				throw new PacketDecodeException("JWT payload must contain 'tid'");
			}
			if(!isset($data["xuid"])) {
				throw new PacketDecodeException("JWT payload must contain 'xuid'");
			}
			if(!isset($data["entitlements"])) {
				throw new PacketDecodeException("JWT payload must contain 'entitlements'");
			}

			try {
				json_decode($data["entitlements"], false, flags: JSON_THROW_ON_ERROR);
			} catch(\Exception $exception) {
				throw new PacketDecodeException($exception->getMessage());
			}

			$this->entries[] = $data["entitlements"];
		}
	}

	protected function encodePayload(PacketSerializer $out) : void{
		$out->putUnsignedVarInt(count($this->entries));
		foreach($this->entries as $entry){
			$out->putString($entry);
		}
	}

	public function handle(PacketHandlerInterface $handler) : bool{
		return $handler->handlePurchaseReceipt($this);
	}
}
