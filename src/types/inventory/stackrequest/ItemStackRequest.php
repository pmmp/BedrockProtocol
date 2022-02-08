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

namespace pocketmine\network\mcpe\protocol\types\inventory\stackrequest;

use pocketmine\network\mcpe\protocol\PacketDecodeException;
use pocketmine\network\mcpe\protocol\serializer\PacketSerializer;
use pocketmine\utils\BinaryDataException;
use function count;

final class ItemStackRequest{

	private int $requestId;
	/** @var ItemStackRequestAction[] */
	private array $actions;
	/**
	 * @var string[]
	 * @phpstan-var list<string>
	 */
	private array $filterStrings;

	/**
	 * @param ItemStackRequestAction[] $actions
	 * @param string[]                 $filterStrings
	 * @phpstan-param list<string> $filterStrings
	 */
	public function __construct(int $requestId, array $actions, array $filterStrings){
		$this->requestId = $requestId;
		$this->actions = $actions;
		$this->filterStrings = $filterStrings;
	}

	public function getRequestId() : int{ return $this->requestId; }

	/** @return ItemStackRequestAction[] */
	public function getActions() : array{ return $this->actions; }

	/**
	 * @return string[]
	 * @phpstan-return list<string>
	 */
	public function getFilterStrings() : array{ return $this->filterStrings; }

	/**
	 * @throws BinaryDataException
	 * @throws PacketDecodeException
	 */
	private static function readAction(PacketSerializer $in, int $typeId) : ItemStackRequestAction{
		switch($typeId){
			case TakeStackRequestAction::ID: return TakeStackRequestAction::read($in);
			case PlaceStackRequestAction::ID: return PlaceStackRequestAction::read($in);
			case SwapStackRequestAction::ID: return SwapStackRequestAction::read($in);
			case DropStackRequestAction::ID: return DropStackRequestAction::read($in);
			case DestroyStackRequestAction::ID: return DestroyStackRequestAction::read($in);
			case CraftingConsumeInputStackRequestAction::ID: return CraftingConsumeInputStackRequestAction::read($in);
			case CraftingMarkSecondaryResultStackRequestAction::ID: return CraftingMarkSecondaryResultStackRequestAction::read($in);
			case PlaceIntoBundleStackRequestAction::ID: return PlaceIntoBundleStackRequestAction::read($in);
			case TakeFromBundleStackRequestAction::ID: return TakeFromBundleStackRequestAction::read($in);
			case LabTableCombineStackRequestAction::ID: return LabTableCombineStackRequestAction::read($in);
			case BeaconPaymentStackRequestAction::ID: return BeaconPaymentStackRequestAction::read($in);
			case MineBlockStackRequestAction::ID: return MineBlockStackRequestAction::read($in);
			case CraftRecipeStackRequestAction::ID: return CraftRecipeStackRequestAction::read($in);
			case CraftRecipeAutoStackRequestAction::ID: return CraftRecipeAutoStackRequestAction::read($in);
			case CreativeCreateStackRequestAction::ID: return CreativeCreateStackRequestAction::read($in);
			case CraftRecipeOptionalStackRequestAction::ID: return CraftRecipeOptionalStackRequestAction::read($in);
			case GrindstoneStackRequestAction::ID: return GrindstoneStackRequestAction::read($in);
			case LoomStackRequestAction::ID: return LoomStackRequestAction::read($in);
			case DeprecatedCraftingNonImplementedStackRequestAction::ID: return DeprecatedCraftingNonImplementedStackRequestAction::read($in);
			case DeprecatedCraftingResultsStackRequestAction::ID: return DeprecatedCraftingResultsStackRequestAction::read($in);
		}
		throw new PacketDecodeException("Unhandled item stack request action type $typeId");
	}

	public static function read(PacketSerializer $in) : self{
		$requestId = $in->readGenericTypeNetworkId();
		$actions = [];
		for($i = 0, $len = $in->getUnsignedVarInt(); $i < $len; ++$i){
			$typeId = $in->getByte();
			$actions[] = self::readAction($in, $typeId);
		}
		$filterStrings = [];
		for($i = 0, $len = $in->getUnsignedVarInt(); $i < $len; ++$i){
			$filterStrings[] = $in->getString();
		}
		return new self($requestId, $actions, $filterStrings);
	}

	public function write(PacketSerializer $out) : void{
		$out->writeGenericTypeNetworkId($this->requestId);
		$out->putUnsignedVarInt(count($this->actions));
		foreach($this->actions as $action){
			$out->putByte($action->getTypeId());
			$action->write($out);
		}
		$out->putUnsignedVarInt(count($this->filterStrings));
		foreach($this->filterStrings as $string){
			$out->putString($string);
		}
	}
}
