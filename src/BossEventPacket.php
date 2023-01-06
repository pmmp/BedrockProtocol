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

use pocketmine\network\mcpe\protocol\serializer\PacketSerializer;
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

	protected function decodePayload(PacketSerializer $in) : void{
		$this->bossActorUniqueId = $in->getActorUniqueId();
		$this->eventType = $in->getUnsignedVarInt();
		switch($this->eventType){
			case self::TYPE_REGISTER_PLAYER:
			case self::TYPE_UNREGISTER_PLAYER:
			case self::TYPE_QUERY:
				$this->playerActorUniqueId = $in->getActorUniqueId();
				break;
			/** @noinspection PhpMissingBreakStatementInspection */
			case self::TYPE_SHOW:
				$this->title = $in->getString();
				$this->healthPercent = $in->getLFloat();
			/** @noinspection PhpMissingBreakStatementInspection */
			case self::TYPE_PROPERTIES:
				$this->darkenScreen = match($raw = $in->getLShort()){
					0 => false,
					1 => true,
					default => throw new PacketDecodeException("Invalid darkenScreen value $raw"),
				};
			case self::TYPE_TEXTURE:
				$this->color = $in->getUnsignedVarInt();
				$this->overlay = $in->getUnsignedVarInt();
				break;
			case self::TYPE_HEALTH_PERCENT:
				$this->healthPercent = $in->getLFloat();
				break;
			case self::TYPE_TITLE:
				$this->title = $in->getString();
				break;
			default:
				break;
		}
	}

	protected function encodePayload(PacketSerializer $out) : void{
		$out->putActorUniqueId($this->bossActorUniqueId);
		$out->putUnsignedVarInt($this->eventType);
		switch($this->eventType){
			case self::TYPE_REGISTER_PLAYER:
			case self::TYPE_UNREGISTER_PLAYER:
			case self::TYPE_QUERY:
				$out->putActorUniqueId($this->playerActorUniqueId);
				break;
			/** @noinspection PhpMissingBreakStatementInspection */
			case self::TYPE_SHOW:
				$out->putString($this->title);
				$out->putLFloat($this->healthPercent);
			/** @noinspection PhpMissingBreakStatementInspection */
			case self::TYPE_PROPERTIES:
				$out->putLShort($this->darkenScreen ? 1 : 0);
			case self::TYPE_TEXTURE:
				$out->putUnsignedVarInt($this->color);
				$out->putUnsignedVarInt($this->overlay);
				break;
			case self::TYPE_HEALTH_PERCENT:
				$out->putLFloat($this->healthPercent);
				break;
			case self::TYPE_TITLE:
				$out->putString($this->title);
				break;
			default:
				break;
		}
	}

	public function handle(PacketHandlerInterface $handler) : bool{
		return $handler->handleBossEvent($this);
	}
}
