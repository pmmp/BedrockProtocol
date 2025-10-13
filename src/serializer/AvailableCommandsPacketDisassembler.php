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
use pocketmine\network\mcpe\protocol\types\command\CommandEnum;
use pocketmine\network\mcpe\protocol\types\command\CommandOverload;
use pocketmine\network\mcpe\protocol\types\command\CommandParameter;
use pocketmine\network\mcpe\protocol\types\command\raw\ChainedSubCommandRawData;
use pocketmine\network\mcpe\protocol\types\command\raw\CommandEnumConstraintRawData;
use pocketmine\network\mcpe\protocol\types\command\raw\CommandEnumRawData;
use pocketmine\network\mcpe\protocol\types\command\raw\CommandRawData;
use pocketmine\network\mcpe\protocol\types\command\raw\CommandSoftEnumRawData;

/**
 * Disassembles low-level AvailableCommandsPacket structures into high-level commands data that can be operated on more
 * easily (no nasty offsets).
 */
final class AvailableCommandsPacketDisassembler{

	private AvailableCommandsPacket $packet;

	/**
	 * @var CommandData[]
	 * @phpstan-var list<CommandData>
	 */
	private array $commandData = [];

	/**
	 * @var CommandEnumConstraintRawData[][][]
	 * @phpstan-var array<int, array<int, list<CommandEnumConstraintRawData>>>
	 */
	private array $enumConstraintIndex = [];

	/**
	 * @var CommandEnum[]
	 * @phpstan-var array<int, CommandEnum>
	 */
	private array $highEnumCache = [];
	/**
	 * @var CommandEnum[]
	 * @phpstan-var array<int, CommandEnum>
	 */
	private array $highSoftEnumCache = [];
	/**
	 * @var ChainedSubCommandData[]
	 * @phpstan-var array<int, ChainedSubCommandData>
	 */
	private array $highChainedSubCommandDataCache = [];

	/**
	 * @var string[]
	 * @phpstan-var array<int, string>
	 */
	private array $unusedEnumValues;
	/**
	 * @var string[]
	 * @phpstan-var array<int, string>
	 */
	private array $unusedPostfixes;

	/**
	 * @var CommandEnumRawData[]
	 * @phpstan-var array<int, CommandEnumRawData>
	 */
	private array $unusedHardEnums;
	/**
	 * @var CommandSoftEnumRawData[]
	 * @phpstan-var array<int, CommandSoftEnumRawData>
	 */
	private array $unusedSoftEnums;
	/**
	 * @var ChainedSubCommandRawData[]
	 * @phpstan-var array<int, ChainedSubCommandRawData>
	 */
	private array $unusedChainedSubCommandData;
	/**
	 * @var string[]
	 * @phpstan-var array<int, string>
	 */
	private array $unusedChainedSubCommandValues;

	private function __construct(AvailableCommandsPacket $packet){
		$this->packet = $packet;
		$this->unusedEnumValues = $packet->getEnumValues();
		$this->unusedHardEnums = $packet->getEnums();
		$this->unusedSoftEnums = $packet->getSoftEnums();
		$this->unusedPostfixes = $packet->getPostfixes();
		$this->unusedChainedSubCommandData = $packet->getChainedSubCommandData();
		$this->unusedChainedSubCommandValues = $packet->getChainedSubCommandValues();
	}

	public static function disassemble(AvailableCommandsPacket $packet) : DisassembledAvailableCommandsData{
		$result = new self($packet);

		//this lets us put the data for the constraints inside the CommandEnum objects directly
		foreach($packet->getEnumConstraints() as $rawConstraintData){
			$result->enumConstraintIndex[$rawConstraintData->getEnumIndex()][$rawConstraintData->getAffectedValueIndex()][] = $rawConstraintData;
		}

		foreach($packet->getCommandData() as $rawData){
			$result->commandData[] = $result->processCommandData($rawData);
		}

		return new DisassembledAvailableCommandsData(
			$result->commandData,
			$result->unusedEnumValues,
			$result->unusedPostfixes,
			$result->unusedHardEnums,
			$result->unusedSoftEnums,
			$result->unusedChainedSubCommandData,
			$result->unusedChainedSubCommandValues
		);
	}

	/**
	 * @throws PacketDecodeException
	 */
	private function lookupHardEnumValue(int $index) : string{
		$value = $this->packet->getEnumValues()[$index] ?? throw new PacketDecodeException("No such enum value index $index");
		unset($this->unusedEnumValues[$index]);
		return $value;
	}

	/**
	 * @throws PacketDecodeException
	 */
	private function lookupHardEnum(int $index) : CommandEnum{
		if(!isset($this->highEnumCache[$index])){
			$rawEnum = $this->packet->getEnums()[$index] ?? throw new PacketDecodeException("No such enum index $index");

			$enumValues = [];
			$enumValueConstraints = [];
			foreach($rawEnum->getValueIndexes() as $valueOffset => $valueIndex){
				$enumValues[] = $this->lookupHardEnumValue($valueIndex);

				$rawConstraints = $this->enumConstraintIndex[$index][$valueIndex] ?? null;
				if($rawConstraints !== null){
					foreach($rawConstraints as $constraint){
						//technically it's possible for multiple constraints to be specified on the same parameter, so
						//this has to be kept as a 2D array :<
						$enumValueConstraints[$valueOffset][] = $constraint->getConstraints();
					}
				}
			}

			$this->highEnumCache[$index] = new CommandEnum($rawEnum->getName(), $enumValues, isSoft: false, constraints: $enumValueConstraints);
			unset($this->unusedHardEnums[$index]);
		}

		return $this->highEnumCache[$index];
	}

	/**
	 * @throws PacketDecodeException
	 */
	private function lookupSoftEnum(int $index) : CommandEnum{
		if(!isset($this->highSoftEnumCache[$index])){
			$rawEnum = $this->packet->getSoftEnums()[$index] ?? throw new PacketDecodeException("No such soft enum index $index");

			$this->highSoftEnumCache[$index] = new CommandEnum($rawEnum->getName(), $rawEnum->getValues(), isSoft: true, constraints: []);
			unset($this->unusedSoftEnums[$index]);
		}

		return $this->highSoftEnumCache[$index];
	}

	/**
	 * @throws PacketDecodeException
	 */
	private function lookupChainedSubCommandValue(int $index) : string{
		$value = $this->packet->getChainedSubCommandValues()[$index] ?? throw new PacketDecodeException("No such chained subcommand value index $index");
		unset($this->unusedChainedSubCommandValues[$index]);
		return $value;
	}

	/**
	 * @throws PacketDecodeException
	 */
	private function lookupChainedSubCommandData(int $index) : ChainedSubCommandData{
		if(!isset($this->highChainedSubCommandDataCache[$index])){
			$rawData = $this->packet->getChainedSubCommandData()[$index] ?? throw new PacketDecodeException("No such chained subcommand index $index");

			$values = [];
			foreach($rawData->getValueData() as $rawValueData){
				$valueName = $this->lookupChainedSubCommandValue($rawValueData->getNameIndex());
				$values[] = new ChainedSubCommandValue($valueName, $rawValueData->getType());
			}

			$this->highChainedSubCommandDataCache[$index] = new ChainedSubCommandData($rawData->getName(), $values);
			unset($this->unusedChainedSubCommandData[$index]);
		}

		return $this->highChainedSubCommandDataCache[$index];
	}

	/**
	 * @throws PacketDecodeException
	 */
	private function lookupPostfix(int $index) : string{
		$value = $this->packet->getPostfixes()[$index] ?? throw new PacketDecodeException("No such postfix index $index");
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
				$highTypeInfo = 0;
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
					$highTypeInfo = $typeInfo & (AvailableCommandsPacket::ARG_FLAG_VALID - 1);
				}else{
					throw new PacketDecodeException("Unrecognized arg flag combination $typeInfo for command " . $rawData->getName() . ", overload $overloadIndex, parameter $parameterIndex");
				}

				$parameters[] = CommandParameter::allFields(
					paramName: $rawParameterData->getName(),
					paramType: $highTypeInfo,
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
