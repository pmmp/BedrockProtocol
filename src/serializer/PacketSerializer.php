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

namespace pocketmine\network\mcpe\protocol\serializer;

use pocketmine\math\Vector3;
use pocketmine\nbt\NbtDataException;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\nbt\TreeRoot;
use pocketmine\network\mcpe\protocol\PacketDecodeException;
use pocketmine\network\mcpe\protocol\types\BlockPosition;
use pocketmine\network\mcpe\protocol\types\BoolGameRule;
use pocketmine\network\mcpe\protocol\types\command\CommandOriginData;
use pocketmine\network\mcpe\protocol\types\entity\Attribute;
use pocketmine\network\mcpe\protocol\types\entity\AttributeModifier;
use pocketmine\network\mcpe\protocol\types\entity\BlockPosMetadataProperty;
use pocketmine\network\mcpe\protocol\types\entity\ByteMetadataProperty;
use pocketmine\network\mcpe\protocol\types\entity\CompoundTagMetadataProperty;
use pocketmine\network\mcpe\protocol\types\entity\EntityLink;
use pocketmine\network\mcpe\protocol\types\entity\FloatMetadataProperty;
use pocketmine\network\mcpe\protocol\types\entity\IntMetadataProperty;
use pocketmine\network\mcpe\protocol\types\entity\LongMetadataProperty;
use pocketmine\network\mcpe\protocol\types\entity\MetadataProperty;
use pocketmine\network\mcpe\protocol\types\entity\ShortMetadataProperty;
use pocketmine\network\mcpe\protocol\types\entity\StringMetadataProperty;
use pocketmine\network\mcpe\protocol\types\entity\Vec3MetadataProperty;
use pocketmine\network\mcpe\protocol\types\FloatGameRule;
use pocketmine\network\mcpe\protocol\types\GameRule;
use pocketmine\network\mcpe\protocol\types\IntGameRule;
use pocketmine\network\mcpe\protocol\types\inventory\ItemStack;
use pocketmine\network\mcpe\protocol\types\inventory\ItemStackWrapper;
use pocketmine\network\mcpe\protocol\types\recipe\ComplexAliasItemDescriptor;
use pocketmine\network\mcpe\protocol\types\recipe\IntIdMetaItemDescriptor;
use pocketmine\network\mcpe\protocol\types\recipe\ItemDescriptorType;
use pocketmine\network\mcpe\protocol\types\recipe\MolangItemDescriptor;
use pocketmine\network\mcpe\protocol\types\recipe\RecipeIngredient;
use pocketmine\network\mcpe\protocol\types\recipe\StringIdMetaItemDescriptor;
use pocketmine\network\mcpe\protocol\types\recipe\TagItemDescriptor;
use pocketmine\network\mcpe\protocol\types\skin\PersonaPieceTintColor;
use pocketmine\network\mcpe\protocol\types\skin\PersonaSkinPiece;
use pocketmine\network\mcpe\protocol\types\skin\SkinAnimation;
use pocketmine\network\mcpe\protocol\types\skin\SkinData;
use pocketmine\network\mcpe\protocol\types\skin\SkinImage;
use pocketmine\network\mcpe\protocol\types\StructureEditorData;
use pocketmine\network\mcpe\protocol\types\StructureSettings;
use pocketmine\utils\Binary;
use pocketmine\utils\BinaryDataException;
use pocketmine\utils\BinaryStream;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;
use function count;
use function strlen;
use function strrev;
use function substr;

class PacketSerializer extends BinaryStream{
	protected function __construct(string $buffer = "", int $offset = 0){
		//overridden to change visibility
		parent::__construct($buffer, $offset);
	}

	public static function encoder() : self{
		return new self();
	}

	public static function decoder(string $buffer, int $offset) : self{
		return new self($buffer, $offset);
	}

	/**
	 * @throws BinaryDataException
	 */
	public function getString() : string{
		return $this->get($this->getUnsignedVarInt());
	}

	public function putString(string $v) : void{
		$this->putUnsignedVarInt(strlen($v));
		$this->put($v);
	}

	/**
	 * @throws BinaryDataException
	 */
	public function getUUID() : UuidInterface{
		//This is two little-endian longs: bytes 7-0 followed by bytes 15-8
		$p1 = strrev($this->get(8));
		$p2 = strrev($this->get(8));
		return Uuid::fromBytes($p1 . $p2);
	}

	public function putUUID(UuidInterface $uuid) : void{
		$bytes = $uuid->getBytes();
		$this->put(strrev(substr($bytes, 0, 8)));
		$this->put(strrev(substr($bytes, 8, 8)));
	}

	public function getSkin() : SkinData{
		$skinId = $this->getString();
		$skinPlayFabId = $this->getString();
		$skinResourcePatch = $this->getString();
		$skinData = $this->getSkinImage();
		$animationCount = $this->getLInt();
		$animations = [];
		for($i = 0; $i < $animationCount; ++$i){
			$skinImage = $this->getSkinImage();
			$animationType = $this->getLInt();
			$animationFrames = $this->getLFloat();
			$expressionType = $this->getLInt();
			$animations[] = new SkinAnimation($skinImage, $animationType, $animationFrames, $expressionType);
		}
		$capeData = $this->getSkinImage();
		$geometryData = $this->getString();
		$geometryDataVersion = $this->getString();
		$animationData = $this->getString();
		$capeId = $this->getString();
		$fullSkinId = $this->getString();
		$armSize = $this->getString();
		$skinColor = $this->getString();
		$personaPieceCount = $this->getLInt();
		$personaPieces = [];
		for($i = 0; $i < $personaPieceCount; ++$i){
			$pieceId = $this->getString();
			$pieceType = $this->getString();
			$packId = $this->getString();
			$isDefaultPiece = $this->getBool();
			$productId = $this->getString();
			$personaPieces[] = new PersonaSkinPiece($pieceId, $pieceType, $packId, $isDefaultPiece, $productId);
		}
		$pieceTintColorCount = $this->getLInt();
		$pieceTintColors = [];
		for($i = 0; $i < $pieceTintColorCount; ++$i){
			$pieceType = $this->getString();
			$colorCount = $this->getLInt();
			$colors = [];
			for($j = 0; $j < $colorCount; ++$j){
				$colors[] = $this->getString();
			}
			$pieceTintColors[] = new PersonaPieceTintColor(
				$pieceType,
				$colors
			);
		}

		$premium = $this->getBool();
		$persona = $this->getBool();
		$capeOnClassic = $this->getBool();
		$isPrimaryUser = $this->getBool();
		$override = $this->getBool();

		return new SkinData(
			$skinId,
			$skinPlayFabId,
			$skinResourcePatch,
			$skinData,
			$animations,
			$capeData,
			$geometryData,
			$geometryDataVersion,
			$animationData,
			$capeId,
			$fullSkinId,
			$armSize,
			$skinColor,
			$personaPieces,
			$pieceTintColors,
			true,
			$premium,
			$persona,
			$capeOnClassic,
			$isPrimaryUser,
			$override,
		);
	}

	public function putSkin(SkinData $skin) : void{
		$this->putString($skin->getSkinId());
		$this->putString($skin->getPlayFabId());
		$this->putString($skin->getResourcePatch());
		$this->putSkinImage($skin->getSkinImage());
		$this->putLInt(count($skin->getAnimations()));
		foreach($skin->getAnimations() as $animation){
			$this->putSkinImage($animation->getImage());
			$this->putLInt($animation->getType());
			$this->putLFloat($animation->getFrames());
			$this->putLInt($animation->getExpressionType());
		}
		$this->putSkinImage($skin->getCapeImage());
		$this->putString($skin->getGeometryData());
		$this->putString($skin->getGeometryDataEngineVersion());
		$this->putString($skin->getAnimationData());
		$this->putString($skin->getCapeId());
		$this->putString($skin->getFullSkinId());
		$this->putString($skin->getArmSize());
		$this->putString($skin->getSkinColor());
		$this->putLInt(count($skin->getPersonaPieces()));
		foreach($skin->getPersonaPieces() as $piece){
			$this->putString($piece->getPieceId());
			$this->putString($piece->getPieceType());
			$this->putString($piece->getPackId());
			$this->putBool($piece->isDefaultPiece());
			$this->putString($piece->getProductId());
		}
		$this->putLInt(count($skin->getPieceTintColors()));
		foreach($skin->getPieceTintColors() as $tint){
			$this->putString($tint->getPieceType());
			$this->putLInt(count($tint->getColors()));
			foreach($tint->getColors() as $color){
				$this->putString($color);
			}
		}
		$this->putBool($skin->isPremium());
		$this->putBool($skin->isPersona());
		$this->putBool($skin->isPersonaCapeOnClassic());
		$this->putBool($skin->isPrimaryUser());
		$this->putBool($skin->isOverride());
	}

	private function getSkinImage() : SkinImage{
		$width = $this->getLInt();
		$height = $this->getLInt();
		$data = $this->getString();
		try{
			return new SkinImage($height, $width, $data);
		}catch(\InvalidArgumentException $e){
			throw new PacketDecodeException($e->getMessage(), 0, $e);
		}
	}

	private function putSkinImage(SkinImage $image) : void{
		$this->putLInt($image->getWidth());
		$this->putLInt($image->getHeight());
		$this->putString($image->getData());
	}

	/**
	 * @return int[]
	 * @phpstan-return array{0: int, 1: int, 2: int}
	 * @throws PacketDecodeException
	 */
	private function getItemStackHeader() : array{
		$id = $this->getVarInt();
		if($id === 0){
			return [0, 0, 0];
		}

		$count = $this->getLShort();
		$meta = $this->getUnsignedVarInt();

		return [$id, $count, $meta];
	}

	private function putItemStackHeader(ItemStack $itemStack) : bool{
		if($itemStack->getId() === 0){
			$this->putVarInt(0);
			return false;
		}

		$this->putVarInt($itemStack->getId());
		$this->putLShort($itemStack->getCount());
		$this->putUnsignedVarInt($itemStack->getMeta());

		return true;
	}

	private function getItemStackFooter(int $id, int $meta, int $count) : ItemStack{
		$blockRuntimeId = $this->getVarInt();
		$rawExtraData = $this->getString();

		return new ItemStack($id, $meta, $count, $blockRuntimeId, $rawExtraData);
	}

	private function putItemStackFooter(ItemStack $itemStack) : void{
		$this->putVarInt($itemStack->getBlockRuntimeId());
		$this->putString($itemStack->getRawExtraData());
	}

	/**
	 * @throws PacketDecodeException
	 * @throws BinaryDataException
	 */
	public function getItemStackWithoutStackId() : ItemStack{
		[$id, $count, $meta] = $this->getItemStackHeader();

		return $id !== 0 ? $this->getItemStackFooter($id, $meta, $count) : ItemStack::null();

	}

	public function putItemStackWithoutStackId(ItemStack $itemStack) : void{
		if($this->putItemStackHeader($itemStack)){
			$this->putItemStackFooter($itemStack);
		}
	}

	public function getItemStackWrapper() : ItemStackWrapper{
		[$id, $count, $meta] = $this->getItemStackHeader();
		if($id === 0){
			return new ItemStackWrapper(0, ItemStack::null());
		}

		$hasNetId = $this->getBool();
		$stackId = $hasNetId ? $this->readServerItemStackId() : 0;

		$itemStack = $this->getItemStackFooter($id, $meta, $count);

		return new ItemStackWrapper($stackId, $itemStack);
	}

	public function putItemStackWrapper(ItemStackWrapper $itemStackWrapper) : void{
		$itemStack = $itemStackWrapper->getItemStack();
		if($this->putItemStackHeader($itemStack)){
			$hasNetId = $itemStackWrapper->getStackId() !== 0;
			$this->putBool($hasNetId);
			if($hasNetId){
				$this->writeServerItemStackId($itemStackWrapper->getStackId());
			}

			$this->putItemStackFooter($itemStack);
		}
	}

	public function getRecipeIngredient() : RecipeIngredient{
		$descriptorType = $this->getByte();
		$descriptor = match($descriptorType){
			ItemDescriptorType::INT_ID_META => IntIdMetaItemDescriptor::read($this),
			ItemDescriptorType::STRING_ID_META => StringIdMetaItemDescriptor::read($this),
			ItemDescriptorType::TAG => TagItemDescriptor::read($this),
			ItemDescriptorType::MOLANG => MolangItemDescriptor::read($this),
			ItemDescriptorType::COMPLEX_ALIAS => ComplexAliasItemDescriptor::read($this),
			default => null
		};
		$count = $this->getVarInt();

		return new RecipeIngredient($descriptor, $count);
	}

	public function putRecipeIngredient(RecipeIngredient $ingredient) : void{
		$type = $ingredient->getDescriptor();

		$this->putByte($type?->getTypeId() ?? 0);
		$type?->write($this);

		$this->putVarInt($ingredient->getCount());
	}

	/**
	 * Decodes entity metadata from the stream.
	 *
	 * @return MetadataProperty[]
	 * @phpstan-return array<int, MetadataProperty>
	 *
	 * @throws PacketDecodeException
	 * @throws BinaryDataException
	 */
	public function getEntityMetadata() : array{
		$count = $this->getUnsignedVarInt();
		$data = [];
		for($i = 0; $i < $count; ++$i){
			$key = $this->getUnsignedVarInt();
			$type = $this->getUnsignedVarInt();

			$data[$key] = $this->readMetadataProperty($type);
		}

		return $data;
	}

	private function readMetadataProperty(int $type) : MetadataProperty{
		return match($type){
			ByteMetadataProperty::ID => ByteMetadataProperty::read($this),
			ShortMetadataProperty::ID => ShortMetadataProperty::read($this),
			IntMetadataProperty::ID => IntMetadataProperty::read($this),
			FloatMetadataProperty::ID => FloatMetadataProperty::read($this),
			StringMetadataProperty::ID => StringMetadataProperty::read($this),
			CompoundTagMetadataProperty::ID => CompoundTagMetadataProperty::read($this),
			BlockPosMetadataProperty::ID => BlockPosMetadataProperty::read($this),
			LongMetadataProperty::ID => LongMetadataProperty::read($this),
			Vec3MetadataProperty::ID => Vec3MetadataProperty::read($this),
			default => throw new PacketDecodeException("Unknown entity metadata type " . $type),
		};
	}

	/**
	 * Writes entity metadata to the packet buffer.
	 *
	 * @param MetadataProperty[] $metadata
	 *
	 * @phpstan-param array<int, MetadataProperty> $metadata
	 */
	public function putEntityMetadata(array $metadata) : void{
		$this->putUnsignedVarInt(count($metadata));
		foreach($metadata as $key => $d){
			$this->putUnsignedVarInt($key);
			$this->putUnsignedVarInt($d->getTypeId());
			$d->write($this);
		}
	}

	/**
	 * Reads a list of Attributes from the stream.
	 * @return Attribute[]
	 *
	 * @throws BinaryDataException
	 */
	public function getAttributeList() : array{
		$list = [];
		$count = $this->getUnsignedVarInt();

		for($i = 0; $i < $count; ++$i){
			$min = $this->getLFloat();
			$max = $this->getLFloat();
			$current = $this->getLFloat();
			$default = $this->getLFloat();
			$id = $this->getString();

			$modifiers = [];
			for($j = 0, $modifierCount = $this->getUnsignedVarInt(); $j < $modifierCount; $j++){
				$modifiers[] = AttributeModifier::read($this);
			}

			$list[] = new Attribute($id, $min, $max, $current, $default, $modifiers);
		}

		return $list;
	}

	/**
	 * Writes a list of Attributes to the packet buffer using the standard format.
	 */
	public function putAttributeList(Attribute ...$attributes) : void{
		$this->putUnsignedVarInt(count($attributes));
		foreach($attributes as $attribute){
			$this->putLFloat($attribute->getMin());
			$this->putLFloat($attribute->getMax());
			$this->putLFloat($attribute->getCurrent());
			$this->putLFloat($attribute->getDefault());
			$this->putString($attribute->getId());

			$this->putUnsignedVarInt(count($attribute->getModifiers()));
			foreach($attribute->getModifiers() as $modifier){
				$modifier->write($this);
			}
		}
	}

	/**
	 * @throws BinaryDataException
	 */
	final public function getActorUniqueId() : int{
		return $this->getVarLong();
	}

	public function putActorUniqueId(int $eid) : void{
		$this->putVarLong($eid);
	}

	/**
	 * @throws BinaryDataException
	 */
	final public function getActorRuntimeId() : int{
		return $this->getUnsignedVarLong();
	}

	public function putActorRuntimeId(int $eid) : void{
		$this->putUnsignedVarLong($eid);
	}

	/**
	 * Reads a block position with unsigned Y coordinate.
	 *
	 * @throws BinaryDataException
	 */
	public function getBlockPosition() : BlockPosition{
		$x = $this->getVarInt();
		$y = Binary::signInt($this->getUnsignedVarInt()); //Y coordinate may be signed, but it's written unsigned :<
		$z = $this->getVarInt();
		return new BlockPosition($x, $y, $z);
	}

	/**
	 * Writes a block position with unsigned Y coordinate.
	 */
	public function putBlockPosition(BlockPosition $blockPosition) : void{
		$this->putVarInt($blockPosition->getX());
		$this->putUnsignedVarInt(Binary::unsignInt($blockPosition->getY())); //Y coordinate may be signed, but it's written unsigned :<
		$this->putVarInt($blockPosition->getZ());
	}

	/**
	 * Reads a block position with a signed Y coordinate.
	 *
	 * @throws BinaryDataException
	 */
	public function getSignedBlockPosition() : BlockPosition{
		$x = $this->getVarInt();
		$y = $this->getVarInt();
		$z = $this->getVarInt();
		return new BlockPosition($x, $y, $z);
	}

	/**
	 * Writes a block position with a signed Y coordinate.
	 */
	public function putSignedBlockPosition(BlockPosition $blockPosition) : void{
		$this->putVarInt($blockPosition->getX());
		$this->putVarInt($blockPosition->getY());
		$this->putVarInt($blockPosition->getZ());
	}

	/**
	 * Reads a floating-point Vector3 object with coordinates rounded to 4 decimal places.
	 *
	 * @throws BinaryDataException
	 */
	public function getVector3() : Vector3{
		$x = $this->getLFloat();
		$y = $this->getLFloat();
		$z = $this->getLFloat();
		return new Vector3($x, $y, $z);
	}

	/**
	 * Writes a floating-point Vector3 object, or 3x zero if null is given.
	 *
	 * Note: ONLY use this where it is reasonable to allow not specifying the vector.
	 * For all other purposes, use the non-nullable version.
	 *
	 * @see PacketSerializer::putVector3()
	 */
	public function putVector3Nullable(?Vector3 $vector) : void{
		if($vector !== null){
			$this->putVector3($vector);
		}else{
			$this->putLFloat(0.0);
			$this->putLFloat(0.0);
			$this->putLFloat(0.0);
		}
	}

	/**
	 * Writes a floating-point Vector3 object
	 */
	public function putVector3(Vector3 $vector) : void{
		$this->putLFloat($vector->x);
		$this->putLFloat($vector->y);
		$this->putLFloat($vector->z);
	}

	/**
	 * @throws BinaryDataException
	 */
	public function getRotationByte() : float{
		return ($this->getByte() * (360 / 256));
	}

	public function putRotationByte(float $rotation) : void{
		$this->putByte((int) ($rotation / (360 / 256)));
	}

	private function readGameRule(int $type, bool $isPlayerModifiable) : GameRule{
		return match($type){
			BoolGameRule::ID => BoolGameRule::decode($this, $isPlayerModifiable),
			IntGameRule::ID => IntGameRule::decode($this, $isPlayerModifiable),
			FloatGameRule::ID => FloatGameRule::decode($this, $isPlayerModifiable),
			default => throw new PacketDecodeException("Unknown gamerule type $type"),
		};
	}

	/**
	 * Reads gamerules
	 *
	 * @return GameRule[] game rule name => value
	 * @phpstan-return array<string, GameRule>
	 *
	 * @throws PacketDecodeException
	 * @throws BinaryDataException
	 */
	public function getGameRules() : array{
		$count = $this->getUnsignedVarInt();
		$rules = [];
		for($i = 0; $i < $count; ++$i){
			$name = $this->getString();
			$isPlayerModifiable = $this->getBool();
			$type = $this->getUnsignedVarInt();
			$rules[$name] = $this->readGameRule($type, $isPlayerModifiable);
		}

		return $rules;
	}

	/**
	 * Writes a gamerule array
	 *
	 * @param GameRule[] $rules
	 * @phpstan-param array<string, GameRule> $rules
	 */
	public function putGameRules(array $rules) : void{
		$this->putUnsignedVarInt(count($rules));
		foreach($rules as $name => $rule){
			$this->putString($name);
			$this->putBool($rule->isPlayerModifiable());
			$this->putUnsignedVarInt($rule->getTypeId());
			$rule->encode($this);
		}
	}

	/**
	 * @throws BinaryDataException
	 */
	public function getEntityLink() : EntityLink{
		$fromActorUniqueId = $this->getActorUniqueId();
		$toActorUniqueId = $this->getActorUniqueId();
		$type = $this->getByte();
		$immediate = $this->getBool();
		$causedByRider = $this->getBool();
		return new EntityLink($fromActorUniqueId, $toActorUniqueId, $type, $immediate, $causedByRider);
	}

	public function putEntityLink(EntityLink $link) : void{
		$this->putActorUniqueId($link->fromActorUniqueId);
		$this->putActorUniqueId($link->toActorUniqueId);
		$this->putByte($link->type);
		$this->putBool($link->immediate);
		$this->putBool($link->causedByRider);
	}

	/**
	 * @throws BinaryDataException
	 */
	public function getCommandOriginData() : CommandOriginData{
		$result = new CommandOriginData();

		$result->type = $this->getUnsignedVarInt();
		$result->uuid = $this->getUUID();
		$result->requestId = $this->getString();

		if($result->type === CommandOriginData::ORIGIN_DEV_CONSOLE or $result->type === CommandOriginData::ORIGIN_TEST){
			$result->playerActorUniqueId = $this->getVarLong();
		}

		return $result;
	}

	public function putCommandOriginData(CommandOriginData $data) : void{
		$this->putUnsignedVarInt($data->type);
		$this->putUUID($data->uuid);
		$this->putString($data->requestId);

		if($data->type === CommandOriginData::ORIGIN_DEV_CONSOLE or $data->type === CommandOriginData::ORIGIN_TEST){
			$this->putVarLong($data->playerActorUniqueId);
		}
	}

	public function getStructureSettings() : StructureSettings{
		$result = new StructureSettings();

		$result->paletteName = $this->getString();

		$result->ignoreEntities = $this->getBool();
		$result->ignoreBlocks = $this->getBool();
		$result->allowNonTickingChunks = $this->getBool();

		$result->dimensions = $this->getBlockPosition();
		$result->offset = $this->getBlockPosition();

		$result->lastTouchedByPlayerID = $this->getActorUniqueId();
		$result->rotation = $this->getByte();
		$result->mirror = $this->getByte();
		$result->animationMode = $this->getByte();
		$result->animationSeconds = $this->getLFloat();
		$result->integrityValue = $this->getLFloat();
		$result->integritySeed = $this->getLInt();
		$result->pivot = $this->getVector3();

		return $result;
	}

	public function putStructureSettings(StructureSettings $structureSettings) : void{
		$this->putString($structureSettings->paletteName);

		$this->putBool($structureSettings->ignoreEntities);
		$this->putBool($structureSettings->ignoreBlocks);
		$this->putBool($structureSettings->allowNonTickingChunks);

		$this->putBlockPosition($structureSettings->dimensions);
		$this->putBlockPosition($structureSettings->offset);

		$this->putActorUniqueId($structureSettings->lastTouchedByPlayerID);
		$this->putByte($structureSettings->rotation);
		$this->putByte($structureSettings->mirror);
		$this->putByte($structureSettings->animationMode);
		$this->putLFloat($structureSettings->animationSeconds);
		$this->putLFloat($structureSettings->integrityValue);
		$this->putLInt($structureSettings->integritySeed);
		$this->putVector3($structureSettings->pivot);
	}

	public function getStructureEditorData() : StructureEditorData{
		$result = new StructureEditorData();

		$result->structureName = $this->getString();
		$result->structureDataField = $this->getString();

		$result->includePlayers = $this->getBool();
		$result->showBoundingBox = $this->getBool();

		$result->structureBlockType = $this->getVarInt();
		$result->structureSettings = $this->getStructureSettings();
		$result->structureRedstoneSaveMode = $this->getVarInt();

		return $result;
	}

	public function putStructureEditorData(StructureEditorData $structureEditorData) : void{
		$this->putString($structureEditorData->structureName);
		$this->putString($structureEditorData->structureDataField);

		$this->putBool($structureEditorData->includePlayers);
		$this->putBool($structureEditorData->showBoundingBox);

		$this->putVarInt($structureEditorData->structureBlockType);
		$this->putStructureSettings($structureEditorData->structureSettings);
		$this->putVarInt($structureEditorData->structureRedstoneSaveMode);
	}

	public function getNbtRoot() : TreeRoot{
		$offset = $this->getOffset();
		try{
			return (new NetworkNbtSerializer())->read($this->getBuffer(), $offset, 512);
		}catch(NbtDataException $e){
			throw PacketDecodeException::wrap($e, "Failed decoding NBT root");
		}finally{
			$this->setOffset($offset);
		}
	}

	public function getNbtCompoundRoot() : CompoundTag{
		try{
			return $this->getNbtRoot()->mustGetCompoundTag();
		}catch(NbtDataException $e){
			throw PacketDecodeException::wrap($e, "Expected TAG_Compound NBT root");
		}
	}

	public function readRecipeNetId() : int{
		return $this->getUnsignedVarInt();
	}

	public function writeRecipeNetId(int $id) : void{
		$this->putUnsignedVarInt($id);
	}

	public function readCreativeItemNetId() : int{
		return $this->getUnsignedVarInt();
	}

	public function writeCreativeItemNetId(int $id) : void{
		$this->putUnsignedVarInt($id);
	}

	/**
	 * This is a union of ItemStackRequestId, LegacyItemStackRequestId, and ServerItemStackId, used in serverbound
	 * packets to allow the client to refer to server known items, or items which may have been modified by a previous
	 * as-yet unacknowledged request from the client.
	 *
	 * - Server itemstack ID is positive
	 * - InventoryTransaction "legacy" request ID is negative and even
	 * - ItemStackRequest request ID is negative and odd
	 * - 0 refers to an empty itemstack (air)
	 */
	public function readItemStackNetIdVariant() : int{
		return $this->getVarInt();
	}

	/**
	 * This is a union of ItemStackRequestId, LegacyItemStackRequestId, and ServerItemStackId, used in serverbound
	 * packets to allow the client to refer to server known items, or items which may have been modified by a previous
	 * as-yet unacknowledged request from the client.
	 */
	public function writeItemStackNetIdVariant(int $id) : void{
		$this->putVarInt($id);
	}

	public function readItemStackRequestId() : int{
		return $this->getVarInt();
	}

	public function writeItemStackRequestId(int $id) : void{
		$this->putVarInt($id);
	}

	public function readLegacyItemStackRequestId() : int{
		return $this->getVarInt();
	}

	public function writeLegacyItemStackRequestId(int $id) : void{
		$this->putVarInt($id);
	}

	public function readServerItemStackId() : int{
		return $this->getVarInt();
	}

	public function writeServerItemStackId(int $id) : void{
		$this->putVarInt($id);
	}

	/**
	 * @phpstan-template T
	 * @phpstan-param \Closure() : T $reader
	 * @phpstan-return T|null
	 */
	public function readOptional(\Closure $reader) : mixed{
		if($this->getBool()){
			return $reader();
		}
		return null;
	}

	/**
	 * @phpstan-template T
	 * @phpstan-param T|null $value
	 * @phpstan-param \Closure(T) : void $writer
	 */
	public function writeOptional(mixed $value, \Closure $writer) : void{
		if($value !== null){
			$this->putBool(true);
			$writer($value);
		}else{
			$this->putBool(false);
		}
	}
}
