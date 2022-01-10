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

use pocketmine\network\mcpe\protocol\ProtocolInfo;
use Ramsey\Uuid\Uuid;

class SkinData{

	public const ARM_SIZE_SLIM = "slim";
	public const ARM_SIZE_WIDE = "wide";

	private string $skinId;
	private string $playFabId;
	private string $resourcePatch;
	private SkinImage $skinImage;
	/** @var SkinAnimation[] */
	private array $animations;
	private SkinImage $capeImage;
	private string $geometryData;
	private string $geometryDataEngineVersion;
	private string $animationData;
	private string $capeId;
	private string $fullSkinId;
	private string $armSize;
	private string $skinColor;
	/** @var PersonaSkinPiece[] */
	private array $personaPieces;
	/** @var PersonaPieceTintColor[] */
	private array $pieceTintColors;
	private bool $isVerified;
	private bool $persona;
	private bool $premium;
	private bool $personaCapeOnClassic;
	private bool $isPrimaryUser;

	/**
	 * @param SkinAnimation[]         $animations
	 * @param PersonaSkinPiece[]      $personaPieces
	 * @param PersonaPieceTintColor[] $pieceTintColors
	 */
	public function __construct(string $skinId, string $playFabId, string $resourcePatch, SkinImage $skinImage, array $animations = [], SkinImage $capeImage = null, string $geometryData = "", string $geometryDataEngineVersion = ProtocolInfo::MINECRAFT_VERSION_NETWORK, string $animationData = "", string $capeId = "", ?string $fullSkinId = null, string $armSize = self::ARM_SIZE_WIDE, string $skinColor = "", array $personaPieces = [], array $pieceTintColors = [], bool $isVerified = true, bool $premium = false, bool $persona = false, bool $personaCapeOnClassic = false, bool $isPrimaryUser = true){
		$this->skinId = $skinId;
		$this->playFabId = $playFabId;
		$this->resourcePatch = $resourcePatch;
		$this->skinImage = $skinImage;
		$this->animations = $animations;
		$this->capeImage = $capeImage ?? new SkinImage(0, 0, "");
		$this->geometryData = $geometryData;
		$this->geometryDataEngineVersion = $geometryDataEngineVersion;
		$this->animationData = $animationData;
		$this->capeId = $capeId;
		//this has to be unique or the client will do stupid things
		$this->fullSkinId = $fullSkinId ?? Uuid::uuid4()->toString();
		$this->armSize = $armSize;
		$this->skinColor = $skinColor;
		$this->personaPieces = $personaPieces;
		$this->pieceTintColors = $pieceTintColors;
		$this->isVerified = $isVerified;
		$this->premium = $premium;
		$this->persona = $persona;
		$this->personaCapeOnClassic = $personaCapeOnClassic;
		$this->isPrimaryUser = $isPrimaryUser;
	}

	public function getSkinId() : string{
		return $this->skinId;
	}

	public function getPlayFabId() : string{ return $this->playFabId; }

	public function getResourcePatch() : string{
		return $this->resourcePatch;
	}

	public function getSkinImage() : SkinImage{
		return $this->skinImage;
	}

	/**
	 * @return SkinAnimation[]
	 */
	public function getAnimations() : array{
		return $this->animations;
	}

	public function getCapeImage() : SkinImage{
		return $this->capeImage;
	}

	public function getGeometryData() : string{
		return $this->geometryData;
	}

	public function getGeometryDataEngineVersion() : string{ return $this->geometryDataEngineVersion; }

	public function getAnimationData() : string{
		return $this->animationData;
	}

	public function getCapeId() : string{
		return $this->capeId;
	}

	public function getFullSkinId() : string{
		return $this->fullSkinId;
	}

	public function getArmSize() : string{
		return $this->armSize;
	}

	public function getSkinColor() : string{
		return $this->skinColor;
	}

	/**
	 * @return PersonaSkinPiece[]
	 */
	public function getPersonaPieces() : array{
		return $this->personaPieces;
	}

	/**
	 * @return PersonaPieceTintColor[]
	 */
	public function getPieceTintColors() : array{
		return $this->pieceTintColors;
	}

	public function isPersona() : bool{
		return $this->persona;
	}

	public function isPremium() : bool{
		return $this->premium;
	}

	public function isPersonaCapeOnClassic() : bool{
		return $this->personaCapeOnClassic;
	}

	public function isPrimaryUser() : bool{ return $this->isPrimaryUser; }

	public function isVerified() : bool{
		return $this->isVerified;
	}

	/**
	 * @internal
	 */
	public function setVerified(bool $verified) : void{
		$this->isVerified = $verified;
	}
}
