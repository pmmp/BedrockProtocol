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
use pmmp\encoding\LE;
use pocketmine\network\mcpe\protocol\serializer\CommonTypes;

class ClientboundDataDrivenUIShowScreenPacket extends DataPacket implements ClientboundPacket{
	public const NETWORK_ID = ProtocolInfo::CLIENTBOUND_DATA_DRIVEN_UI_SHOW_SCREEN_PACKET;

	private string $screenId;
	private int $formId;
	private ?int $dataInstanceId;

	/**
	 * @generate-create-func
	 */
	public static function create(string $screenId, int $formId, ?int $dataInstanceId) : self{
		$result = new self;
		$result->screenId = $screenId;
		$result->formId = $formId;
		$result->dataInstanceId = $dataInstanceId;
		return $result;
	}

	public function getScreenId() : string{ return $this->screenId; }

	public function getFormId() : int{ return $this->formId; }

	public function getDataInstanceId() : ?int{ return $this->dataInstanceId; }

	protected function decodePayload(ByteBufferReader $in) : void{
		$this->screenId = CommonTypes::getString($in);
		$this->formId = LE::readUnsignedInt($in);
		$this->dataInstanceId = CommonTypes::readOptional($in, LE::readUnsignedInt(...));
	}

	protected function encodePayload(ByteBufferWriter $out) : void{
		CommonTypes::putString($out, $this->screenId);
		LE::writeUnsignedInt($out, $this->formId);
		CommonTypes::writeOptional($out, $this->dataInstanceId, LE::writeUnsignedInt(...));
	}

	public function handle(PacketHandlerInterface $handler) : bool{
		return $handler->handleClientboundDataDrivenUIShowScreen($this);
	}
}
