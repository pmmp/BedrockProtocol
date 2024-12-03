<?php

declare(strict_types=1);

namespace pocketmine\network\mcpe\protocol;

use pocketmine\network\mcpe\protocol\serializer\PacketSerializer;

class UpdateSettingsPacket extends DataPacket implements ServerboundPacket{
	public const NETWORK_ID = ProtocolInfo::UPDATE_SETTINGS_PACKET;

	/** @var int */
	public int $defaultGameMode;
	/** @var int */
	public int $gameMode;

	protected function decodePayload(PacketSerializer $in) : void{
		$this->defaultGameMode = $in->getByte();
		$this->gameMode = $in->getByte();
	}

	protected function encodePayload(PacketSerializer $out) : void{
		$out->putByte($this->defaultGameMode);
		$out->putByte($this->gameMode);
	}

	public static function create(int $defaultGameMode, int $gameMode) : self{
		$packet = new self();
		$packet->defaultGameMode = $defaultGameMode;
		$packet->gameMode = $gameMode;
		return $packet;
	}

	public function handle(PacketHandlerInterface $handler) : bool{
		return $handler->handleUpdateSettingsPacket($this);
	}
}
