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

class SetTitlePacket extends DataPacket implements ClientboundPacket{
	public const NETWORK_ID = ProtocolInfo::SET_TITLE_PACKET;

	public const TYPE_CLEAR_TITLE = 0;
	public const TYPE_RESET_TITLE = 1;
	public const TYPE_SET_TITLE = 2;
	public const TYPE_SET_SUBTITLE = 3;
	public const TYPE_SET_ACTIONBAR_MESSAGE = 4;
	public const TYPE_SET_ANIMATION_TIMES = 5;
	public const TYPE_SET_TITLE_JSON = 6;
	public const TYPE_SET_SUBTITLE_JSON = 7;
	public const TYPE_SET_ACTIONBAR_MESSAGE_JSON = 8;

	public int $type;
	public string $text = "";
	public int $fadeInTime = 0;
	public int $stayTime = 0;
	public int $fadeOutTime = 0;
	public string $xuid = "";
	public string $platformOnlineId = "";
	public string $filteredTitleText = "";

	/**
	 * @generate-create-func
	 */
	public static function create(
		int $type,
		string $text,
		int $fadeInTime,
		int $stayTime,
		int $fadeOutTime,
		string $xuid,
		string $platformOnlineId,
		string $filteredTitleText,
	) : self{
		$result = new self;
		$result->type = $type;
		$result->text = $text;
		$result->fadeInTime = $fadeInTime;
		$result->stayTime = $stayTime;
		$result->fadeOutTime = $fadeOutTime;
		$result->xuid = $xuid;
		$result->platformOnlineId = $platformOnlineId;
		$result->filteredTitleText = $filteredTitleText;
		return $result;
	}

	protected function decodePayload(ByteBufferReader $in) : void{
		$this->type = VarInt::readSignedInt($in);
		$this->text = CommonTypes::getString($in);
		$this->fadeInTime = VarInt::readSignedInt($in);
		$this->stayTime = VarInt::readSignedInt($in);
		$this->fadeOutTime = VarInt::readSignedInt($in);
		$this->xuid = CommonTypes::getString($in);
		$this->platformOnlineId = CommonTypes::getString($in);
		$this->filteredTitleText = CommonTypes::getString($in);
	}

	protected function encodePayload(ByteBufferWriter $out) : void{
		VarInt::writeSignedInt($out, $this->type);
		CommonTypes::putString($out, $this->text);
		VarInt::writeSignedInt($out, $this->fadeInTime);
		VarInt::writeSignedInt($out, $this->stayTime);
		VarInt::writeSignedInt($out, $this->fadeOutTime);
		CommonTypes::putString($out, $this->xuid);
		CommonTypes::putString($out, $this->platformOnlineId);
		CommonTypes::putString($out, $this->filteredTitleText);
	}

	public function handle(PacketHandlerInterface $handler) : bool{
		return $handler->handleSetTitle($this);
	}

	private static function type(int $type) : self{
		$result = new self;
		$result->type = $type;
		return $result;
	}

	private static function text(int $type, string $text) : self{
		$result = self::type($type);
		$result->text = $text;
		return $result;
	}

	public static function title(string $text) : self{
		return self::text(self::TYPE_SET_TITLE, $text);
	}

	public static function subtitle(string $text) : self{
		return self::text(self::TYPE_SET_SUBTITLE, $text);
	}

	public static function actionBarMessage(string $text) : self{
		return self::text(self::TYPE_SET_ACTIONBAR_MESSAGE, $text);
	}

	public static function clearTitle() : self{
		return self::type(self::TYPE_CLEAR_TITLE);
	}

	public static function resetTitleOptions() : self{
		return self::type(self::TYPE_RESET_TITLE);
	}

	public static function setAnimationTimes(int $fadeIn, int $stay, int $fadeOut) : self{
		$result = self::type(self::TYPE_SET_ANIMATION_TIMES);
		$result->fadeInTime = $fadeIn;
		$result->stayTime = $stay;
		$result->fadeOutTime = $fadeOut;
		return $result;
	}
}
