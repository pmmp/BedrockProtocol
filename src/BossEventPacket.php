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
use pmmp\encoding\VarInt;
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

	public int $playerActorUniqueId;
	public float $healthPercent;
	public string $title;
	public string $filteredTitle;
	public bool $darkenScreen;
	public int $color;
	public int $overlay;

	private static function base(int $bossActorUniqueId, int $eventId) : self{
		$result = new self;
		$result->bossActorUniqueId = $bossActorUniqueId;
		$result->eventType = $eventId;
		return $result;
	}

	public static function show(int $bossActorUniqueId, string $title, float $healthPercent, bool $darkenScreen = false, int $color = BossBarColor::PURPLE, int $overlay = 0) : self{
		$result = self::base($bossActorUniqueId, self::TYPE_SHOW);
		$result->title = $title;
		$result->filteredTitle = $title;
		$result->healthPercent = $healthPercent;
		$result->darkenScreen = $darkenScreen;
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

	public static function properties(int $bossActorUniqueId, bool $darkenScreen, int $color = BossBarColor::PURPLE, int $overlay = 0) : self{
		$result = self::base($bossActorUniqueId, self::TYPE_PROPERTIES);
		$result->darkenScreen = $darkenScreen;
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
		$this->eventType = VarInt::readUnsignedInt($in);
		switch($this->eventType){
			case self::TYPE_REGISTER_PLAYER:
			case self::TYPE_UNREGISTER_PLAYER:
			case self::TYPE_QUERY:
				$this->playerActorUniqueId = CommonTypes::getActorUniqueId($in);
				break;
			/** @noinspection PhpMissingBreakStatementInspection */
			case self::TYPE_SHOW:
				$this->title = CommonTypes::getString($in);
				$this->filteredTitle = CommonTypes::getString($in);
				$this->healthPercent = LE::readFloat($in);
			/** @noinspection PhpMissingBreakStatementInspection */
			case self::TYPE_PROPERTIES:
				$this->darkenScreen = match($raw = LE::readUnsignedShort($in)){
					0 => false,
					1 => true,
					default => throw new PacketDecodeException("Invalid darkenScreen value $raw"),
				};
			case self::TYPE_TEXTURE:
				$this->color = VarInt::readUnsignedInt($in);
				$this->overlay = VarInt::readUnsignedInt($in);
				break;
			case self::TYPE_HEALTH_PERCENT:
				$this->healthPercent = LE::readFloat($in);
				break;
			case self::TYPE_TITLE:
				$this->title = CommonTypes::getString($in);
				$this->filteredTitle = CommonTypes::getString($in);
				break;
			default:
				break;
		}
	}

	protected function encodePayload(ByteBufferWriter $out) : void{
		CommonTypes::putActorUniqueId($out, $this->bossActorUniqueId);
		VarInt::writeUnsignedInt($out, $this->eventType);
		switch($this->eventType){
			case self::TYPE_REGISTER_PLAYER:
			case self::TYPE_UNREGISTER_PLAYER:
			case self::TYPE_QUERY:
				CommonTypes::putActorUniqueId($out, $this->playerActorUniqueId);
				break;
			/** @noinspection PhpMissingBreakStatementInspection */
			case self::TYPE_SHOW:
				CommonTypes::putString($out, $this->title);
				CommonTypes::putString($out, $this->filteredTitle);
				LE::writeFloat($out, $this->healthPercent);
			/** @noinspection PhpMissingBreakStatementInspection */
			case self::TYPE_PROPERTIES:
				LE::writeUnsignedShort($out, $this->darkenScreen ? 1 : 0);
			case self::TYPE_TEXTURE:
				VarInt::writeUnsignedInt($out, $this->color);
				VarInt::writeUnsignedInt($out, $this->overlay);
				break;
			case self::TYPE_HEALTH_PERCENT:
				LE::writeFloat($out, $this->healthPercent);
				break;
			case self::TYPE_TITLE:
				CommonTypes::putString($out, $this->title);
				CommonTypes::putString($out, $this->filteredTitle);
				break;
			default:
				break;
		}
	}

	public function handle(PacketHandlerInterface $handler) : bool{
		return $handler->handleBossEvent($this);
	}
}
