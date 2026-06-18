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
use pmmp\encoding\LE;
use pocketmine\network\mcpe\protocol\serializer\CommonTypes;
use pocketmine\network\mcpe\protocol\types\BossBarColor;

class BossEventPacket extends DataPacket implements ClientboundPacket, ServerboundPacket{
	public const NETWORK_ID = ProtocolInfo::BOSS_EVENT_PACKET;

	/** S2C: Shows the boss-bar to the player. */
	public const TYPE_SHOW = 0;
	/** C2S: Registers a player to a boss fight. */
	public const TYPE_REGISTER_PLAYER = 1;
	/** S2C: Removes the boss-bar from the client. */
	public const TYPE_HIDE = 2;
	/** C2S: Unregisters a player from a boss fight. */
	public const TYPE_UNREGISTER_PLAYER = 3;
	/** S2C: Sets the bar percentage. */
	public const TYPE_HEALTH_PERCENT = 4;
	/** S2C: Sets title of the bar. */
	public const TYPE_TITLE = 5;
	/** S2C: Updates misc properties of the bar and environment. */
	public const TYPE_PROPERTIES = 6;
	/** S2C: Updates boss-bar colour and overlay texture. */
	public const TYPE_TEXTURE = 7;
	/** C2S: Client asking the server to resend all boss data. */
	public const TYPE_QUERY = 8;

	public int $bossActorUniqueId;
	public int $eventType;

	public int $playerActorUniqueId = 0;
	public float $healthPercent = 0.0;
	public string $title = "";
	public string $filteredTitle = "";
	public int $color = BossBarColor::YELLOW;
	public int $overlay = 0;

	private static function base(int $bossActorUniqueId, int $eventId) : self{
		$result = new self;
		$result->bossActorUniqueId = $bossActorUniqueId;
		$result->eventType = $eventId;
		return $result;
	}

	public static function show(int $bossActorUniqueId, string $title, float $healthPercent, int $color = BossBarColor::PURPLE, int $overlay = 0) : self{
		$result = self::base($bossActorUniqueId, self::TYPE_SHOW);
		$result->title = $title;
		$result->filteredTitle = $title;
		$result->healthPercent = $healthPercent;
		$result->color = $color;
		$result->overlay = $overlay;
		return $result;
	}

	public static function hide(int $bossActorUniqueId) : self{
		return self::base($bossActorUniqueId, self::TYPE_HIDE);
	}

	public static function registerPlayer(int $bossActorUniqueId, int $playerActorUniqueId) : self{
		$result = self::base($bossActorUniqueId, self::TYPE_REGISTER_PLAYER);
		$result->playerActorUniqueId = $playerActorUniqueId;
		return $result;
	}

	public static function unregisterPlayer(int $bossActorUniqueId, int $playerActorUniqueId) : self{
		$result = self::base($bossActorUniqueId, self::TYPE_UNREGISTER_PLAYER);
		$result->playerActorUniqueId = $playerActorUniqueId;
		return $result;
	}

	public static function healthPercent(int $bossActorUniqueId, float $healthPercent) : self{
		$result = self::base($bossActorUniqueId, self::TYPE_HEALTH_PERCENT);
		$result->healthPercent = $healthPercent;
		return $result;
	}

	public static function title(int $bossActorUniqueId, string $title) : self{
		$result = self::base($bossActorUniqueId, self::TYPE_TITLE);
		$result->title = $title;
		$result->filteredTitle = $title;
		return $result;
	}

	public static function properties(int $bossActorUniqueId, int $color = BossBarColor::PURPLE, int $overlay = 0) : self{
		$result = self::base($bossActorUniqueId, self::TYPE_PROPERTIES);
		$result->color = $color;
		$result->overlay = $overlay;
		return $result;
	}

	public static function query(int $bossActorUniqueId, int $playerActorUniqueId) : self{
		$result = self::base($bossActorUniqueId, self::TYPE_QUERY);
		$result->playerActorUniqueId = $playerActorUniqueId;
		return $result;
	}

	protected function decodePayload(ByteBufferReader $in) : void{
		$this->bossActorUniqueId = CommonTypes::getActorUniqueId($in);
		$this->playerActorUniqueId = CommonTypes::getActorUniqueId($in);
		$this->eventType = Byte::readUnsigned($in);
		$this->title = CommonTypes::getString($in);
		$this->filteredTitle = CommonTypes::getString($in);
		$this->healthPercent = LE::readFloat($in);
		$this->color = Byte::readUnsigned($in);
		$this->overlay = Byte::readUnsigned($in);
	}

	protected function encodePayload(ByteBufferWriter $out) : void{
		CommonTypes::putActorUniqueId($out, $this->bossActorUniqueId);
		CommonTypes::putActorUniqueId($out, $this->playerActorUniqueId);
		Byte::writeUnsigned($out, $this->eventType);
		CommonTypes::putString($out, $this->title);
		CommonTypes::putString($out, $this->filteredTitle);
		LE::writeFloat($out, $this->healthPercent);
		Byte::writeUnsigned($out, $this->color);
		Byte::writeUnsigned($out, $this->overlay);
	}

	public function handle(PacketHandlerInterface $handler) : bool{
		return $handler->handleBossEvent($this);
	}
}
