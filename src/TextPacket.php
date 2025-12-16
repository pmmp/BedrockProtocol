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

	private const CATEGORY_MESSAGE_ONLY = 0;
	private const CATEGORY_AUTHORED_MESSAGE = 1;
	private const CATEGORY_MESSAGE_WITH_PARAMETERS = 2;

	private const CATEGORY_DUMMY_STRINGS = [
		self::CATEGORY_MESSAGE_ONLY => [
			'raw',
			'tip',
			'systemMessage',
			'textObjectWhisper',
			'textObjectAnnouncement',
			'textObject'
		],
		self::CATEGORY_AUTHORED_MESSAGE => [
			'chat',
			'whisper',
			'announcement'
		],
		self::CATEGORY_MESSAGE_WITH_PARAMETERS => [
			'translate',
			'popup',
			'jukeboxPopup',
		]
	];

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
	public ?string $filteredMessage = null;

	private static function messageOnly(int $type, string $message) : self{
		$result = new self;
		$result->type = $type;
		//TODO: HACK! Empty message crashes or bugs out client in 1.21.130
		$result->message = $message === "" ? " " : $message;
		return $result;
	}

	/**
	 * @param string[] $parameters
	 */
	private static function baseTranslation(int $type, string $key, array $parameters) : self{
		$result = new self;
		$result->type = $type;
		$result->needsTranslation = true;
		//TODO: HACK! Empty message crashes or bugs out client in 1.21.130
		$result->message = $key === "" ? " " : $key;
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
		$this->needsTranslation = CommonTypes::getBool($in);

		$category = Byte::readUnsigned($in);
		$expectedDummyStrings = self::CATEGORY_DUMMY_STRINGS[$category] ?? throw new PacketDecodeException("Unknown category ID $category");
		foreach($expectedDummyStrings as $k => $expectedDummyString){
			$actual = CommonTypes::getString($in);
			if($expectedDummyString !== $actual){
				throw new PacketDecodeException("Dummy string mismatch for category $category at position $k: expected $expectedDummyString, got $actual");
			}
		}

		$this->type = Byte::readUnsigned($in);
		switch($this->type){
			case self::TYPE_CHAT:
			case self::TYPE_WHISPER:
			/** @noinspection PhpMissingBreakStatementInspection */
			case self::TYPE_ANNOUNCEMENT:
				if($category !== self::CATEGORY_AUTHORED_MESSAGE){
					throw new PacketDecodeException("Decoded TextPacket has invalid structure: type {$this->type} requires category CATEGORY_AUTHORED_MESSAGE");
				}
				$this->sourceName = CommonTypes::getString($in);
				$this->message = CommonTypes::getString($in);
				break;
			case self::TYPE_RAW:
			case self::TYPE_TIP:
			case self::TYPE_SYSTEM:
			case self::TYPE_JSON_WHISPER:
			case self::TYPE_JSON:
			case self::TYPE_JSON_ANNOUNCEMENT:
				if($category !== self::CATEGORY_MESSAGE_ONLY){
					throw new PacketDecodeException("Decoded TextPacket has invalid structure: type {$this->type} requires category CATEGORY_MESSAGE_ONLY");
				}
				$this->message = CommonTypes::getString($in);
				break;
			case self::TYPE_TRANSLATION:
			case self::TYPE_POPUP:
			case self::TYPE_JUKEBOX_POPUP:
				if($category !== self::CATEGORY_MESSAGE_WITH_PARAMETERS){
					throw new PacketDecodeException("Decoded TextPacket has invalid structure: type {$this->type} requires category CATEGORY_MESSAGE_WITH_PARAMETERS");
				}
				$this->message = CommonTypes::getString($in);
				$count = VarInt::readUnsignedInt($in);
				for($i = 0; $i < $count; ++$i){
					$this->parameters[] = CommonTypes::getString($in);
				}
				break;
		}

		$this->xboxUserId = CommonTypes::getString($in);
		$this->platformChatId = CommonTypes::getString($in);
		$this->filteredMessage = CommonTypes::readOptional($in, CommonTypes::getString(...));
	}

	protected function encodePayload(ByteBufferWriter $out) : void{
		CommonTypes::putBool($out, $this->needsTranslation);

		$category = match ($this->type) {
			self::TYPE_RAW,
			self::TYPE_TIP,
			self::TYPE_SYSTEM,
			self::TYPE_JSON_WHISPER,
			self::TYPE_JSON_ANNOUNCEMENT,
			self::TYPE_JSON => self::CATEGORY_MESSAGE_ONLY,

			self::TYPE_CHAT,
			self::TYPE_WHISPER,
			self::TYPE_ANNOUNCEMENT => self::CATEGORY_AUTHORED_MESSAGE,

			self::TYPE_TRANSLATION,
			self::TYPE_POPUP,
			self::TYPE_JUKEBOX_POPUP => self::CATEGORY_MESSAGE_WITH_PARAMETERS,

			default => throw new \LogicException("Invalid TextPacket type: $this->type")
		};
		Byte::writeUnsigned($out, $category);
		foreach(self::CATEGORY_DUMMY_STRINGS[$category] as $dummyString){
			CommonTypes::putString($out, $dummyString);
		}

		Byte::writeUnsigned($out, $this->type);
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
		CommonTypes::writeOptional($out, $this->filteredMessage, CommonTypes::putString(...));
	}

	public function handle(PacketHandlerInterface $handler) : bool{
		return $handler->handleText($this);
	}
}
