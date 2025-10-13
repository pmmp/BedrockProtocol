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
use pocketmine\network\mcpe\protocol\types\command\ChainedSubCommandData;
use pocketmine\network\mcpe\protocol\types\command\CommandData;
use pocketmine\network\mcpe\protocol\types\command\CommandEnum;
use pocketmine\network\mcpe\protocol\types\command\raw\ChainedSubCommandRawData;
use pocketmine\network\mcpe\protocol\types\command\raw\ChainedSubCommandValueRawData;
use pocketmine\network\mcpe\protocol\types\command\raw\CommandEnumConstraintRawData;
use pocketmine\network\mcpe\protocol\types\command\raw\CommandEnumRawData;
use pocketmine\network\mcpe\protocol\types\command\raw\CommandOverloadRawData;
use pocketmine\network\mcpe\protocol\types\command\raw\CommandParameterRawData;
use pocketmine\network\mcpe\protocol\types\command\raw\CommandRawData;
use pocketmine\network\mcpe\protocol\types\command\raw\CommandSoftEnumRawData;
use function count;
use function spl_object_id;

/**
 * Assembles high-level commands data into low-level AvailableCommandsPacket structures
 */
final class AvailableCommandsPacketAssembler{

	/**
	 * @var int[]
	 * @phpstan-var array<int, int>
	 */
	private array $enumIndexes = [];
	/**
	 * @var int[]
	 * @phpstan-var array<string, int>
	 */
	private array $enumValueIndexes = [];
	/**
	 * @var int[]
	 * @phpstan-var array<int, int>
	 */
	private array $softEnumIndexes = [];
	/**
	 * @var int[]
	 * @phpstan-var array<string, int>
	 */
	private array $postfixIndexes = [];
	/**
	 * @var int[]
	 * @phpstan-var array<int, int>
	 */
	private array $chainedSubCommandDataIndexes = [];
	/**
	 * @var int[]
	 * @phpstan-var array<string, int>
	 */
	private array $chainedSubCommandValueIndexes = [];

	/**
	 * @var string[]
	 * @phpstan-var list<string>
	 */
	private array $enumValues = [];
	/**
	 * @var string[]
	 * @phpstan-var list<string>
	 */
	private array $chainedSubCommandValues = [];
	/**
	 * @var string[]
	 * @phpstan-var list<string>
	 */
	private array $postfixes = [];
	/**
	 * @var CommandEnumRawData[]
	 * @phpstan-var list<CommandEnumRawData>
	 */
	private array $enums = [];
	/**
	 * @var ChainedSubCommandRawData[]
	 * @phpstan-var list<ChainedSubCommandRawData>
	 */
	private array $chainedSubCommandData = [];
	/**
	 * @var CommandRawData[]
	 * @phpstan-var list<CommandRawData>
	 */
	private array $commandData = [];
	/**
	 * @var CommandSoftEnumRawData[]
	 * @phpstan-var list<CommandSoftEnumRawData>
	 */
	private array $softEnums = [];
	/**
	 * @var CommandEnumConstraintRawData[]
	 * @phpstan-var list<CommandEnumConstraintRawData>
	 */
	private array $enumConstraints = [];

	/**
	 * @param CommandData[]                       $commandData
	 * @param CommandEnum[]                       $hardcodedEnums
	 * @param CommandEnum[]                       $hardcodedSoftEnums
	 *
	 * @phpstan-param list<CommandData>           $commandData
	 * @phpstan-param list<CommandEnum>           $hardcodedEnums
	 * @phpstan-param list<CommandEnum>           $hardcodedSoftEnums
	 */
	public static function pack(
		array $commandData,
		array $hardcodedEnums,
		array $hardcodedSoftEnums
	) : AvailableCommandsPacket{
		$builder = new self();
		foreach($commandData as $data){
			$builder->addCommandData($data);
		}
		foreach($hardcodedEnums as $enum){
			$builder->addHardEnum($enum);
		}
		foreach($hardcodedSoftEnums as $enum){
			$builder->addSoftEnum($enum);
		}

		return AvailableCommandsPacket::create(
			enumValues: $builder->enumValues,
			chainedSubCommandValues: $builder->chainedSubCommandValues,
			postfixes: $builder->postfixes,
			enums: $builder->enums,
			chainedSubCommandData: $builder->chainedSubCommandData,
			commandData: $builder->commandData,
			softEnums: $builder->softEnums,
			enumConstraints: $builder->enumConstraints
		);
	}

	private function addEnumValue(string $str) : int{
		if(!isset($this->enumValueIndexes[$str])){
			$this->enumValueIndexes[$str] = count($this->enumValues);
			$this->enumValues[] = $str;
		}
		return $this->enumValueIndexes[$str];
	}

	private function addHardEnum(CommandEnum $enum) : int{
		if($enum->isSoft()){
			//TODO: shitty API design
			throw new \InvalidArgumentException("Can't pass a soft enum here");
		}

		$key = spl_object_id($enum);
		if(!isset($this->enumIndexes[$key])){
			$valueIndexes = [];
			foreach($enum->getValues() as $str){
				$valueIndexes[] = $this->addEnumValue($str);
			}
			$this->enumIndexes[$key] = count($this->enums);
			$this->enums[] = new CommandEnumRawData($enum->getName(), $valueIndexes);

			foreach($enum->getConstraints() as $valueOffset => $constraintList){
				$affectedValue = $enum->getValues()[$valueOffset] ?? throw new \LogicException("CommandEnum's constructor should've checked this");
				$affectedValueIndex = $this->enumValueIndexes[$affectedValue] ?? throw new \LogicException("We just added this value to the enum value index, it should always be set");
				foreach($constraintList as $constraints){
					$this->enumConstraints[] = new CommandEnumConstraintRawData($affectedValueIndex, $this->enumIndexes[$key], $constraints);
				}
			}
		}

		return $this->enumIndexes[$key];
	}

	private function addSoftEnum(CommandEnum $enum) : int{
		if(!$enum->isSoft()){
			throw new \InvalidArgumentException("Can't pass a hard enum here");
		}
		$key = spl_object_id($enum);

		if(!isset($this->softEnumIndexes[$key])){
			$this->softEnumIndexes[$key] = count($this->softEnums);
			$this->softEnums[] = new CommandSoftEnumRawData($enum->getName(), $enum->getValues());
		}

		return $this->softEnumIndexes[$key];
	}

	private function addPostfix(string $postfix) : int{
		if(!isset($this->postfixIndexes[$postfix])){
			$this->postfixIndexes[$postfix] = count($this->postfixes);
			$this->postfixes[] = $postfix;
		}
		return $this->postfixIndexes[$postfix];
	}

	private function addChainedSubCommandValueName(string $valueName) : int{
		if(!isset($this->chainedSubCommandValueIndexes[$valueName])){
			$this->chainedSubCommandValueIndexes[$valueName] = count($this->chainedSubCommandValues);
			$this->chainedSubCommandValues[] = $valueName;
		}
		return $this->chainedSubCommandValueIndexes[$valueName];
	}

	private function addChainedSubCommandData(ChainedSubCommandData $data) : int{
		$key = spl_object_id($data);

		if(!isset($this->chainedSubCommandDataIndexes[$key])){
			$rawValueData = [];
			foreach($data->getValues() as $value){
				$valueNameIndex = $this->addChainedSubCommandValueName($value->getName());
				$rawValueData[] = new ChainedSubCommandValueRawData($valueNameIndex, $value->getType());
			}

			$this->chainedSubCommandDataIndexes[$key] = count($this->chainedSubCommandData);
			$this->chainedSubCommandData[] = new ChainedSubCommandRawData($data->getName(), $rawValueData);
		}

		return $this->chainedSubCommandDataIndexes[$key];
	}

	private function addCommandData(CommandData $commandData) : void{
		$aliasesIndex = $commandData->aliases !== null ? $this->addHardEnum($commandData->aliases) : -1;

		$chainedSubCommandDataIndexes = [];
		foreach($commandData->getChainedSubCommandData() as $chainedSubCommandData){
			$chainedSubCommandDataIndexes[] = $this->addChainedSubCommandData($chainedSubCommandData);
		}

		$rawOverloadData = [];
		foreach($commandData->getOverloads() as $overload){
			$rawParameterData = [];

			foreach($overload->getParameters() as $parameter){
				if($parameter->enum !== null){
					if($parameter->enum->isSoft()){
						$enumIndex = $this->addSoftEnum($parameter->enum);
						$typeInfo = AvailableCommandsPacket::ARG_FLAG_SOFT_ENUM | AvailableCommandsPacket::ARG_FLAG_VALID | $enumIndex;
					}else{
						$enumIndex = $this->addHardEnum($parameter->enum);
						$typeInfo = AvailableCommandsPacket::ARG_FLAG_ENUM | AvailableCommandsPacket::ARG_FLAG_VALID | $enumIndex;
					}
				}elseif($parameter->postfix !== null){
					$postfixIndex = $this->addPostfix($parameter->postfix);
					$typeInfo = AvailableCommandsPacket::ARG_FLAG_POSTFIX | $postfixIndex;
				}else{
					//mask this to prevent unwanted flags sneaking in
					$typeInfo = AvailableCommandsPacket::ARG_FLAG_VALID | ($parameter->paramType & AvailableCommandsPacket::ARG_FLAG_VALID - 1);
				}

				$rawParameterData[] = new CommandParameterRawData($parameter->paramName, $typeInfo, $parameter->isOptional, $parameter->flags);
			}

			$rawOverloadData[] = new CommandOverloadRawData($overload->isChaining(), $rawParameterData);
		}

		$this->commandData[] = new CommandRawData(
			name: $commandData->getName(),
			description: $commandData->getDescription(),
			flags: $commandData->getFlags(),
			permission: $commandData->getPermission(),
			aliasEnumIndex: $aliasesIndex,
			chainedSubCommandDataIndexes: $chainedSubCommandDataIndexes,
			overloads: $rawOverloadData
		);
	}
}
