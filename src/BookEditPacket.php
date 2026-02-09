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
use pmmp\encoding\VarInt;
use pocketmine\network\mcpe\protocol\serializer\CommonTypes;

class BookEditPacket extends DataPacket implements ServerboundPacket{
	public const NETWORK_ID = ProtocolInfo::BOOK_EDIT_PACKET;

	public const TYPE_REPLACE_PAGE = 0;
	public const TYPE_ADD_PAGE = 1;
	public const TYPE_DELETE_PAGE = 2;
	public const TYPE_SWAP_PAGES = 3;
	public const TYPE_SIGN_BOOK = 4;

	public int $type;
	public int $inventorySlot;
	public int $pageNumber;
	public int $secondaryPageNumber;
	public string $text;
	public string $photoName;
	public string $title;
	public string $author;
	public string $xuid;

	protected function decodePayload(ByteBufferReader $in) : void{
		$this->inventorySlot = VarInt::readUnsignedInt($in);
		$this->type = VarInt::readUnsignedInt($in);

		switch($this->type){
			case self::TYPE_REPLACE_PAGE:
			case self::TYPE_ADD_PAGE:
				$this->pageNumber = VarInt::readUnsignedInt($in);
				$this->text = CommonTypes::getString($in);
				$this->photoName = CommonTypes::getString($in);
				break;
			case self::TYPE_DELETE_PAGE:
				$this->pageNumber = VarInt::readUnsignedInt($in);
				break;
			case self::TYPE_SWAP_PAGES:
				$this->pageNumber = VarInt::readUnsignedInt($in);
				$this->secondaryPageNumber = VarInt::readUnsignedInt($in);
				break;
			case self::TYPE_SIGN_BOOK:
				$this->title = CommonTypes::getString($in);
				$this->author = CommonTypes::getString($in);
				$this->xuid = CommonTypes::getString($in);
				break;
			default:
				throw new PacketDecodeException("Unknown book edit type $this->type!");
		}
	}

	protected function encodePayload(ByteBufferWriter $out) : void{
		VarInt::writeUnsignedInt($out, $this->inventorySlot);
		VarInt::writeUnsignedInt($out, $this->type);

		switch($this->type){
			case self::TYPE_REPLACE_PAGE:
			case self::TYPE_ADD_PAGE:
				VarInt::writeUnsignedInt($out, $this->pageNumber);
				CommonTypes::putString($out, $this->text);
				CommonTypes::putString($out, $this->photoName);
				break;
			case self::TYPE_DELETE_PAGE:
				VarInt::writeUnsignedInt($out, $this->pageNumber);
				break;
			case self::TYPE_SWAP_PAGES:
				VarInt::writeUnsignedInt($out, $this->pageNumber);
				VarInt::writeUnsignedInt($out, $this->secondaryPageNumber);
				break;
			case self::TYPE_SIGN_BOOK:
				CommonTypes::putString($out, $this->title);
				CommonTypes::putString($out, $this->author);
				CommonTypes::putString($out, $this->xuid);
				break;
			default:
				throw new \InvalidArgumentException("Unknown book edit type $this->type!");
		}
	}

	public function handle(PacketHandlerInterface $handler) : bool{
		return $handler->handleBookEdit($this);
	}
}
