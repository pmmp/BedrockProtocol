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

use pocketmine\network\mcpe\protocol\AvailableCommandsPacket;
use pocketmine\network\mcpe\protocol\PacketDecodeException;
use pocketmine\network\mcpe\protocol\types\command\ChainedSubCommandData;
use pocketmine\network\mcpe\protocol\types\command\ChainedSubCommandValue;
use pocketmine\network\mcpe\protocol\types\command\CommandData;
use pocketmine\network\mcpe\protocol\types\command\CommandHardEnum;
use pocketmine\network\mcpe\protocol\types\command\CommandOverload;
use pocketmine\network\mcpe\protocol\types\command\CommandParameter;
use pocketmine\network\mcpe\protocol\types\command\CommandSoftEnum;
use pocketmine\network\mcpe\protocol\types\command\ConstrainedEnumValue;
use pocketmine\network\mcpe\protocol\types\command\raw\ChainedSubCommandRawData;
use pocketmine\network\mcpe\protocol\types\command\raw\CommandEnumConstraintRawData;
use pocketmine\network\mcpe\protocol\types\command\raw\CommandEnumRawData;
use pocketmine\network\mcpe\protocol\types\command\raw\CommandRawData;

/**
 * Disassembles low-level AvailableCommandsPacket structures into high-level commands data that can be operated on more
 * easily (no nasty offsets).
 */
final class AvailableCommandsPacketDisassembler{

	private AvailableCommandsPacket $packet;

	/**
	 * @var CommandEnumConstraintRawData[][]
	 * @phpstan-var array<int, array<int, CommandEnumConstraintRawData>>
	 */
	private array $enumConstraintIndex = [];

	/**
	 * @var CommandHardEnum[]
	 * @phpstan-var array<int, CommandHardEnum>
	 */
	private array $linkedEnumCache = [];
	/**
	 * @var ChainedSubCommandData[]
	 * @phpstan-var array<int, ChainedSubCommandData>
	 */
	private array $linkedChainedSubCommandDataCache = [];

	/**
	 * @var string[]
	 * @phpstan-var array<int, string>
	 */
	private array $unusedHardEnumValues;
	/**
	 * @var string[]
	 * @phpstan-var array<int, string>
	 */
	private array $unusedPostfixes;

	/**
	 * @var CommandEnumRawData[]
	 * @phpstan-var array<int, CommandEnumRawData>
	 */
	private array $unusedHardEnumRawData;
	/**
	 * @var CommandSoftEnum[]
	 * @phpstan-var array<int, CommandSoftEnum>
	 */
	private array $unusedSoftEnums;
	/**
	 * @var ChainedSubCommandRawData[]
	 * @phpstan-var array<int, ChainedSubCommandRawData>
	 */
	private array $unusedChainedSubCommandRawData;
	/**
	 * @var string[]
	 * @phpstan-var array<int, string>
	 */
	private array $unusedChainedSubCommandValues;

	private function __construct(AvailableCommandsPacket $packet){
		$this->packet = $packet;
		$this->unusedHardEnumValues = $packet->enumValues;
		$this->unusedHardEnumRawData = $packet->enums;
		$this->unusedSoftEnums = $packet->softEnums;
		$this->unusedPostfixes = $packet->postfixes;
		$this->unusedChainedSubCommandRawData = $packet->chainedSubCommandData;
		$this->unusedChainedSubCommandValues = $packet->chainedSubCommandValues;
	}

	public static function disassemble(AvailableCommandsPacket $packet) : DisassembledAvailableCommandsData{
		$result = new self($packet);

		//this lets us put the data for the constraints inside the CommandEnum objects directly
		$repeatedEnumConstraints = [];
		foreach($packet->enumConstraints as $index => $rawConstraintData){
			$enumIndex = $rawConstraintData->getEnumIndex();
			$affectedValueIndex = $rawConstraintData->getAffectedValueIndex();
			if(isset($result->enumConstraintIndex[$enumIndex][$affectedValueIndex])){
				$repeatedEnumConstraints[$index] = $rawConstraintData;
			}else{
				$result->enumConstraintIndex[$rawConstraintData->getEnumIndex()][$rawConstraintData->getAffectedValueIndex()] = $rawConstraintData;
			}
		}

		$unusedCommandData = [];
		foreach($packet->commandData as $rawData){
			$unusedCommandData[] = $result->processCommandData($rawData);
		}
		$unusedHardEnums = [];
		foreach($result->unusedHardEnumRawData as $index => $rawData){
			$unusedHardEnums[$index] = $result->lookupHardEnum($index);
		}
		$unusedChainedSubCommandData = [];
		foreach($result->unusedChainedSubCommandRawData as $index => $rawData){
			$unusedChainedSubCommandData[$index] = $result->lookupChainedSubCommandData($index);
		}

		return new DisassembledAvailableCommandsData(
			$unusedCommandData,
			$result->unusedHardEnumValues,
			$result->unusedPostfixes,
			$unusedHardEnums,
			$result->unusedSoftEnums,
			$unusedChainedSubCommandData,
			$result->unusedChainedSubCommandValues,
			$repeatedEnumConstraints
		);
	}

	/**
	 * @throws PacketDecodeException
	 */
	private function lookupHardEnumValue(int $index) : string{
		$value = $this->packet->enumValues[$index] ?? throw new PacketDecodeException("No such enum value index $index");
		unset($this->unusedHardEnumValues[$index]);
		return $value;
	}

	/**
	 * @throws PacketDecodeException
	 */
	private function lookupHardEnum(int $index) : CommandHardEnum{
		if(!isset($this->linkedEnumCache[$index])){
			$rawEnum = $this->packet->enums[$index] ?? throw new PacketDecodeException("No such enum index $index");

			$enumValues = [];
			foreach($rawEnum->getValueIndexes() as $valueIndex){
				$value = $this->lookupHardEnumValue($valueIndex);

				$rawConstraint = $this->enumConstraintIndex[$index][$valueIndex] ?? null;
				if($rawConstraint !== null){
					$enumValues[] = new ConstrainedEnumValue($value, $rawConstraint->getConstraints());
				}else{
					$enumValues[] = $value;
				}
			}

			$this->linkedEnumCache[$index] = new CommandHardEnum($rawEnum->getName(), $enumValues);
			unset($this->unusedHardEnumRawData[$index]);
		}

		return $this->linkedEnumCache[$index];
	}

	/**
	 * @throws PacketDecodeException
	 */
	private function lookupSoftEnum(int $index) : CommandSoftEnum{
		//no conversion needed - these are fully self-contained
		return $this->packet->softEnums[$index] ?? throw new PacketDecodeException("No such soft enum index $index");
	}

	/**
	 * @throws PacketDecodeException
	 */
	private function lookupChainedSubCommandValue(int $index) : string{
		$value = $this->packet->chainedSubCommandValues[$index] ?? throw new PacketDecodeException("No such chained subcommand value index $index");
		unset($this->unusedChainedSubCommandValues[$index]);
		return $value;
	}

	/**
	 * @throws PacketDecodeException
	 */
	private function lookupChainedSubCommandData(int $index) : ChainedSubCommandData{
		if(!isset($this->linkedChainedSubCommandDataCache[$index])){
			$rawData = $this->packet->chainedSubCommandData[$index] ?? throw new PacketDecodeException("No such chained subcommand index $index");

			$values = [];
			foreach($rawData->getValueData() as $rawValueData){
				$valueName = $this->lookupChainedSubCommandValue($rawValueData->getNameIndex());
				$values[] = new ChainedSubCommandValue($valueName, $rawValueData->getType());
			}

			$this->linkedChainedSubCommandDataCache[$index] = new ChainedSubCommandData($rawData->getName(), $values);
			unset($this->unusedChainedSubCommandRawData[$index]);
		}

		return $this->linkedChainedSubCommandDataCache[$index];
	}

	/**
	 * @throws PacketDecodeException
	 */
	private function lookupPostfix(int $index) : string{
		$value = $this->packet->postfixes[$index] ?? throw new PacketDecodeException("No such postfix index $index");
		unset($this->unusedPostfixes[$index]);
		return $value;
	}

	private function processCommandData(CommandRawData $rawData) : CommandData{
		$aliasesIndex = $rawData->getAliasEnumIndex();
		$aliasesEnum = $aliasesIndex === -1 ? null : $this->lookupHardEnum($aliasesIndex);

		$chainedSubCommandData = [];
		foreach($rawData->getChainedSubCommandDataIndexes() as $dataIndex){
			$chainedSubCommandData[] = $this->lookupChainedSubCommandData($dataIndex);
		}

		$overloads = [];
		foreach($rawData->getOverloads() as $overloadIndex => $rawOverloadData){
			$parameters = [];
			foreach($rawOverloadData->getParameters() as $parameterIndex => $rawParameterData){
				$typeInfo = $rawParameterData->getTypeInfo();
				$flags = $typeInfo & (
					AvailableCommandsPacket::ARG_FLAG_ENUM |
					AvailableCommandsPacket::ARG_FLAG_SOFT_ENUM |
					AvailableCommandsPacket::ARG_FLAG_POSTFIX |
					AvailableCommandsPacket::ARG_FLAG_VALID
				);
				//these flags are mutually exclusive - more than one is an error
				$enum = null;
				$postfix = null;
				$highLevelTypeInfo = 0;
				if($flags === (AvailableCommandsPacket::ARG_FLAG_ENUM | AvailableCommandsPacket::ARG_FLAG_VALID)){
					$index = $typeInfo & (AvailableCommandsPacket::ARG_FLAG_VALID - 1);
					$enum = $this->lookupHardEnum($index);
				}elseif($flags === (AvailableCommandsPacket::ARG_FLAG_SOFT_ENUM | AvailableCommandsPacket::ARG_FLAG_VALID)){
					$index = $typeInfo & (AvailableCommandsPacket::ARG_FLAG_VALID - 1);
					$enum = $this->lookupSoftEnum($index);
				}elseif($flags === AvailableCommandsPacket::ARG_FLAG_POSTFIX){
					$index = $typeInfo & (AvailableCommandsPacket::ARG_FLAG_POSTFIX - 1);
					$postfix = $this->lookupPostfix($index);
				}elseif($flags === AvailableCommandsPacket::ARG_FLAG_VALID){
					$highLevelTypeInfo = $typeInfo & (AvailableCommandsPacket::ARG_FLAG_VALID - 1);
				}else{
					throw new PacketDecodeException("Unrecognized arg flag combination $typeInfo for command " . $rawData->getName() . ", overload $overloadIndex, parameter $parameterIndex");
				}

				$parameters[] = CommandParameter::allFields(
					paramName: $rawParameterData->getName(),
					paramType: $highLevelTypeInfo,
					isOptional: $rawParameterData->isOptional(),
					flags: $rawParameterData->getFlags(),
					enum: $enum,
					postfix: $postfix
				);
			}

			$overloads[] = new CommandOverload($rawOverloadData->isChaining(), $parameters);
		}

		return new CommandData(
			name: $rawData->getName(),
			description: $rawData->getDescription(),
			flags: $rawData->getFlags(),
			permission: $rawData->getPermission(),
			aliases: $aliasesEnum,
			overloads: $overloads,
			chainedSubCommandData: $chainedSubCommandData
		);
	}
}
