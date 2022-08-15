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

namespace pocketmine\network\mcpe\protocol\types\skin;

final class PersonaSkinPiece{

	public const PIECE_TYPE_PERSONA_BODY = "persona_body";
	public const PIECE_TYPE_PERSONA_BOTTOM = "persona_bottom";
	public const PIECE_TYPE_PERSONA_EYES = "persona_eyes";
	public const PIECE_TYPE_PERSONA_FACIAL_HAIR = "persona_facial_hair";
	public const PIECE_TYPE_PERSONA_FEET = "persona_feet";
	public const PIECE_TYPE_PERSONA_HAIR = "persona_hair";
	public const PIECE_TYPE_PERSONA_MOUTH = "persona_mouth";
	public const PIECE_TYPE_PERSONA_SKELETON = "persona_skeleton";
	public const PIECE_TYPE_PERSONA_SKIN = "persona_skin";
	public const PIECE_TYPE_PERSONA_TOP = "persona_top";

	public function __construct(
		private string $pieceId,
		private string $pieceType,
		private string $packId,
		private bool $isDefaultPiece,
		private string $productId
	){}

	public function getPieceId() : string{
		return $this->pieceId;
	}

	public function getPieceType() : string{
		return $this->pieceType;
	}

	public function getPackId() : string{
		return $this->packId;
	}

	public function isDefaultPiece() : bool{
		return $this->isDefaultPiece;
	}

	public function getProductId() : string{
		return $this->productId;
	}
}
