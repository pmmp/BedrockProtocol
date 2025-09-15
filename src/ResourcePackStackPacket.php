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
use pocketmine\network\mcpe\protocol\types\Experiments;
use pocketmine\network\mcpe\protocol\types\resourcepacks\ResourcePackStackEntry;
use function count;

class ResourcePackStackPacket extends DataPacket implements ClientboundPacket{
	public const NETWORK_ID = ProtocolInfo::RESOURCE_PACK_STACK_PACKET;

	/** @var ResourcePackStackEntry[] */
	public array $resourcePackStack = [];
	/** @var ResourcePackStackEntry[] */
	public array $behaviorPackStack = [];
	public bool $mustAccept = false;
	public string $baseGameVersion = ProtocolInfo::MINECRAFT_VERSION_NETWORK;
	public Experiments $experiments;
	public bool $useVanillaEditorPacks;

	/**
	 * @generate-create-func
	 * @param ResourcePackStackEntry[] $resourcePackStack
	 * @param ResourcePackStackEntry[] $behaviorPackStack
	 */
	public static function create(array $resourcePackStack, array $behaviorPackStack, bool $mustAccept, string $baseGameVersion, Experiments $experiments, bool $useVanillaEditorPacks) : self{
		$result = new self;
		$result->resourcePackStack = $resourcePackStack;
		$result->behaviorPackStack = $behaviorPackStack;
		$result->mustAccept = $mustAccept;
		$result->baseGameVersion = $baseGameVersion;
		$result->experiments = $experiments;
		$result->useVanillaEditorPacks = $useVanillaEditorPacks;
		return $result;
	}

	protected function decodePayload(ByteBufferReader $in) : void{
		$this->mustAccept = CommonTypes::getBool($in);
		$behaviorPackCount = VarInt::readUnsignedInt($in);
		while($behaviorPackCount-- > 0){
			$this->behaviorPackStack[] = ResourcePackStackEntry::read($in);
		}

		$resourcePackCount = VarInt::readUnsignedInt($in);
		while($resourcePackCount-- > 0){
			$this->resourcePackStack[] = ResourcePackStackEntry::read($in);
		}

		$this->baseGameVersion = CommonTypes::getString($in);
		$this->experiments = Experiments::read($in);
		$this->useVanillaEditorPacks = CommonTypes::getBool($in);
	}

	protected function encodePayload(ByteBufferWriter $out) : void{
		CommonTypes::putBool($out, $this->mustAccept);

		VarInt::writeUnsignedInt($out, count($this->behaviorPackStack));
		foreach($this->behaviorPackStack as $entry){
			$entry->write($out);
		}

		VarInt::writeUnsignedInt($out, count($this->resourcePackStack));
		foreach($this->resourcePackStack as $entry){
			$entry->write($out);
		}

		CommonTypes::putString($out, $this->baseGameVersion);
		$this->experiments->write($out);
		CommonTypes::putBool($out, $this->useVanillaEditorPacks);
	}

	public function handle(PacketHandlerInterface $handler) : bool{
		return $handler->handleResourcePackStack($this);
	}
}
