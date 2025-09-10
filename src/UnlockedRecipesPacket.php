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
use function count;

class UnlockedRecipesPacket extends DataPacket implements ClientboundPacket{
	public const NETWORK_ID = ProtocolInfo::UNLOCKED_RECIPES_PACKET;

	public const TYPE_EMPTY = 0;
	public const TYPE_INITIALLY_UNLOCKED = 1;
	public const TYPE_NEWLY_UNLOCKED = 2;
	public const TYPE_REMOVE = 3;
	public const TYPE_REMOVE_ALL = 4;

	private int $type;
	/** @var string[] */
	private array $recipes;

	/**
	 * @generate-create-func
	 * @param string[] $recipes
	 */
	public static function create(int $type, array $recipes) : self{
		$result = new self;
		$result->type = $type;
		$result->recipes = $recipes;
		return $result;
	}

	public function getType() : int{ return $this->type; }

	/**
	 * @return string[]
	 */
	public function getRecipes() : array{ return $this->recipes; }

	protected function decodePayload(ByteBufferReader $in) : void{
		$this->type = LE::readUnsignedInt($in);
		$this->recipes = [];
		for($i = 0, $count = VarInt::readUnsignedInt($in); $i < $count; $i++){
			$this->recipes[] = CommonTypes::getString($in);
		}
	}

	protected function encodePayload(ByteBufferWriter $out) : void{
		LE::writeUnsignedInt($out, $this->type);
		VarInt::writeUnsignedInt($out, count($this->recipes));
		foreach($this->recipes as $recipe){
			CommonTypes::putString($out, $recipe);
		}
	}

	public function handle(PacketHandlerInterface $handler) : bool{
		return $handler->handleUnlockedRecipes($this);
	}
}
