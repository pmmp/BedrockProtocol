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
use function count;

class TextPacket extends DataPacket implements ClientboundPacket, ServerboundPacket{
	public const NETWORK_ID = ProtocolInfo::TEXT_PACKET;

	public const TYPE_RAW = 0;
	public const TYPE_CHAT = 1;
	public const TYPE_TRANSLATION = 2;
	public const TYPE_POPUP = 3;
	public const TYPE_JUKEBOX_POPUP = 4;
	public const TYPE_TIP = 5;
	public const TYPE_SYSTEM = 6;
	public const TYPE_WHISPER = 7;
	public const TYPE_ANNOUNCEMENT = 8;
	public const TYPE_JSON_WHISPER = 9;
	public const TYPE_JSON = 10;
	public const TYPE_JSON_ANNOUNCEMENT = 11;

	public int $type;
	public bool $needsTranslation = false;
	public string $sourceName;
	public string $message;
	/** @var string[] */
	public array $parameters = [];
	public string $xboxUserId = "";
	public string $platformChatId = "";
	public string $filteredMessage = "";

	private static function messageOnly(int $type, string $message) : self{
		$result = new self;
		$result->type = $type;
		$result->message = $message;
		return $result;
	}

	/**
	 * @param string[] $parameters
	 */
	private static function baseTranslation(int $type, string $key, array $parameters) : self{
		$result = new self;
		$result->type = $type;
		$result->needsTranslation = true;
		$result->message = $key;
		$result->parameters = $parameters;
		return $result;
	}

	public static function raw(string $message) : self{
		return self::messageOnly(self::TYPE_RAW, $message);
	}

	/**
	 * @param string[]  $parameters
	 */
	public static function translation(string $key, array $parameters = []) : self{
		return self::baseTranslation(self::TYPE_TRANSLATION, $key, $parameters);
	}

	public static function popup(string $message) : self{
		return self::messageOnly(self::TYPE_POPUP, $message);
	}

	/**
	 * @param string[] $parameters
	 */
	public static function translatedPopup(string $key, array $parameters = []) : self{
		return self::baseTranslation(self::TYPE_POPUP, $key, $parameters);
	}

	/**
	 * @param string[] $parameters
	 */
	public static function jukeboxPopup(string $key, array $parameters = []) : self{
		return self::baseTranslation(self::TYPE_JUKEBOX_POPUP, $key, $parameters);
	}

	public static function tip(string $message) : self{
		return self::messageOnly(self::TYPE_TIP, $message);
	}

	protected function decodePayload(ByteBufferReader $in) : void{
		$this->type = Byte::readUnsigned($in);
		$this->needsTranslation = CommonTypes::getBool($in);
		switch($this->type){
			case self::TYPE_CHAT:
			case self::TYPE_WHISPER:
			/** @noinspection PhpMissingBreakStatementInspection */
			case self::TYPE_ANNOUNCEMENT:
				$this->sourceName = CommonTypes::getString($in);
			case self::TYPE_RAW:
			case self::TYPE_TIP:
			case self::TYPE_SYSTEM:
			case self::TYPE_JSON_WHISPER:
			case self::TYPE_JSON:
			case self::TYPE_JSON_ANNOUNCEMENT:
				$this->message = CommonTypes::getString($in);
				break;

			case self::TYPE_TRANSLATION:
			case self::TYPE_POPUP:
			case self::TYPE_JUKEBOX_POPUP:
				$this->message = CommonTypes::getString($in);
				$count = VarInt::readUnsignedInt($in);
				for($i = 0; $i < $count; ++$i){
					$this->parameters[] = CommonTypes::getString($in);
				}
				break;
		}

		$this->xboxUserId = CommonTypes::getString($in);
		$this->platformChatId = CommonTypes::getString($in);
		$this->filteredMessage = CommonTypes::getString($in);
	}

	protected function encodePayload(ByteBufferWriter $out) : void{
		Byte::writeUnsigned($out, $this->type);
		CommonTypes::putBool($out, $this->needsTranslation);
		switch($this->type){
			case self::TYPE_CHAT:
			case self::TYPE_WHISPER:
			/** @noinspection PhpMissingBreakStatementInspection */
			case self::TYPE_ANNOUNCEMENT:
				CommonTypes::putString($out, $this->sourceName);
			case self::TYPE_RAW:
			case self::TYPE_TIP:
			case self::TYPE_SYSTEM:
			case self::TYPE_JSON_WHISPER:
			case self::TYPE_JSON:
			case self::TYPE_JSON_ANNOUNCEMENT:
				CommonTypes::putString($out, $this->message);
				break;

			case self::TYPE_TRANSLATION:
			case self::TYPE_POPUP:
			case self::TYPE_JUKEBOX_POPUP:
				CommonTypes::putString($out, $this->message);
				VarInt::writeUnsignedInt($out, count($this->parameters));
				foreach($this->parameters as $p){
					CommonTypes::putString($out, $p);
				}
				break;
		}

		CommonTypes::putString($out, $this->xboxUserId);
		CommonTypes::putString($out, $this->platformChatId);
		CommonTypes::putString($out, $this->filteredMessage);
	}

	public function handle(PacketHandlerInterface $handler) : bool{
		return $handler->handleText($this);
	}
}
