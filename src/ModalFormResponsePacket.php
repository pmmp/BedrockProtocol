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

class ModalFormResponsePacket extends DataPacket implements ServerboundPacket{
	public const NETWORK_ID = ProtocolInfo::MODAL_FORM_RESPONSE_PACKET;

	public const CANCEL_REASON_CLOSED = 0;
	/** Sent if a form is sent when the player is on a loading screen */
	public const CANCEL_REASON_USER_BUSY = 1;

	public int $formId;
	public ?string $formData; //json
	public ?int $cancelReason;

	/**
	 * @generate-create-func
	 */
	private static function create(int $formId, ?string $formData, ?int $cancelReason) : self{
		$result = new self;
		$result->formId = $formId;
		$result->formData = $formData;
		$result->cancelReason = $cancelReason;
		return $result;
	}

	public static function response(int $formId, string $formData) : self{
		return self::create($formId, $formData, null);
	}

	public static function cancel(int $formId, int $cancelReason) : self{
		return self::create($formId, null, $cancelReason);
	}

	protected function decodePayload(ByteBufferReader $in) : void{
		$this->formId = VarInt::readUnsignedInt($in);
		$this->formData = CommonTypes::readOptional($in, CommonTypes::getString(...));
		$this->cancelReason = CommonTypes::readOptional($in, Byte::readUnsigned(...));
	}

	protected function encodePayload(ByteBufferWriter $out) : void{
		VarInt::writeUnsignedInt($out, $this->formId);

		CommonTypes::writeOptional($out, $this->formData, CommonTypes::putString(...));
		CommonTypes::writeOptional($out, $this->cancelReason, Byte::writeUnsigned(...));
	}

	public function handle(PacketHandlerInterface $handler) : bool{
		return $handler->handleModalFormResponse($this);
	}
}
