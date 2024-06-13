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

use pocketmine\network\mcpe\protocol\serializer\PacketSerializer;
use pocketmine\network\mcpe\protocol\types\command\ChainedSubCommandData;
use pocketmine\network\mcpe\protocol\types\command\ChainedSubCommandValue;
use pocketmine\network\mcpe\protocol\types\command\CommandData;
use pocketmine\network\mcpe\protocol\types\command\CommandEnum;
use pocketmine\network\mcpe\protocol\types\command\CommandEnumConstraint;
use pocketmine\network\mcpe\protocol\types\command\CommandOverload;
use pocketmine\network\mcpe\protocol\types\command\CommandParameter;
use pocketmine\network\mcpe\protocol\types\command\CommandParameterTypes as ArgTypes;
use pocketmine\utils\BinaryDataException;
use function array_search;
use function count;
use function dechex;

class AvailableCommandsPacket extends DataPacket implements ClientboundPacket{
	public const NETWORK_ID = ProtocolInfo::AVAILABLE_COMMANDS_PACKET;

	/**
	 * This flag is set on all types EXCEPT the POSTFIX type. Not completely sure what this is for, but it is required
	 * for the argtype to work correctly. VALID seems as good a name as any.
	 */
	public const ARG_FLAG_VALID = 0x100000;

	/**
	 * Basic parameter types. These must be combined with the ARG_FLAG_VALID constant.
	 * ARG_FLAG_VALID | (type const)
	 */
	public const ARG_TYPE_INT = ArgTypes::INT;
	public const ARG_TYPE_FLOAT = ArgTypes::VAL;
	public const ARG_TYPE_VALUE = ArgTypes::RVAL;
	public const ARG_TYPE_WILDCARD_INT = ArgTypes::WILDCARDINT;
	public const ARG_TYPE_OPERATOR = ArgTypes::OPERATOR;
	public const ARG_TYPE_COMPARE_OPERATOR = ArgTypes::COMPAREOPERATOR;
	public const ARG_TYPE_TARGET = ArgTypes::SELECTION;

	public const ARG_TYPE_WILDCARD_TARGET = ArgTypes::WILDCARDSELECTION;

	public const ARG_TYPE_FILEPATH = ArgTypes::PATHCOMMAND;

	public const ARG_TYPE_FULL_INTEGER_RANGE = ArgTypes::FULLINTEGERRANGE;

	public const ARG_TYPE_EQUIPMENT_SLOT = ArgTypes::EQUIPMENTSLOTENUM;
	public const ARG_TYPE_STRING = ArgTypes::ID;

	public const ARG_TYPE_INT_POSITION = ArgTypes::POSITION;
	public const ARG_TYPE_POSITION = ArgTypes::POSITION_FLOAT;

	public const ARG_TYPE_MESSAGE = ArgTypes::MESSAGE_ROOT;

	public const ARG_TYPE_RAWTEXT = ArgTypes::RAWTEXT;

	public const ARG_TYPE_JSON = ArgTypes::JSON_OBJECT;

	public const ARG_TYPE_BLOCK_STATES = ArgTypes::BLOCK_STATE_ARRAY;

	public const ARG_TYPE_COMMAND = ArgTypes::CODEBUILDERARGS;

	/**
	 * Enums are a little different: they are composed as follows:
	 * ARG_FLAG_ENUM | ARG_FLAG_VALID | (enum index)
	 */
	public const ARG_FLAG_ENUM = 0x200000;

	/** This is used for /xp <level: int>L. It can only be applied to integer parameters. */
	public const ARG_FLAG_POSTFIX = 0x1000000;

	public const ARG_FLAG_SOFT_ENUM = 0x4000000;

	public const HARDCODED_ENUM_NAMES = [
		"CommandName" => true
	];

	/**
	 * @var CommandData[]
	 * List of command data, including name, description, alias indexes and parameters.
	 */
	public array $commandData = [];

	/**
	 * @var CommandEnum[]
	 * List of enums which aren't directly referenced by any vanilla command.
	 * This is used for the `CommandName` enum, which is a magic enum used by the `command` argument type.
	 */
	public array $hardcodedEnums = [];

	/**
	 * @var CommandEnum[]
	 * List of dynamic command enums, also referred to as "soft" enums. These can by dynamically updated mid-game
	 * without resending this packet.
	 */
	public array $softEnums = [];

	/**
	 * @var CommandEnumConstraint[]
	 * List of constraints for enum members. Used to constrain gamerules that can bechanged in nocheats mode and more.
	 */
	public array $enumConstraints = [];

	/**
	 * @generate-create-func
	 * @param CommandData[]           $commandData
	 * @param CommandEnum[]           $hardcodedEnums
	 * @param CommandEnum[]           $softEnums
	 * @param CommandEnumConstraint[] $enumConstraints
	 */
	public static function create(array $commandData, array $hardcodedEnums, array $softEnums, array $enumConstraints) : self{
		$result = new self;
		$result->commandData = $commandData;
		$result->hardcodedEnums = $hardcodedEnums;
		$result->softEnums = $softEnums;
		$result->enumConstraints = $enumConstraints;
		return $result;
	}

	protected function decodePayload(PacketSerializer $in) : void{
		/** @var string[] $enumValues */
		$enumValues = [];
		for($i = 0, $enumValuesCount = $in->getUnsignedVarInt(); $i < $enumValuesCount; ++$i){
			$enumValues[] = $in->getString();
		}

		/** @var string[] $chainedSubcommandValueNames */
		$chainedSubcommandValueNames = [];
		for($i = 0, $count = $in->getUnsignedVarInt(); $i < $count; ++$i){
			$chainedSubcommandValueNames[] = $in->getString();
		}

		/** @var string[] $postfixes */
		$postfixes = [];
		for($i = 0, $count = $in->getUnsignedVarInt(); $i < $count; ++$i){
			$postfixes[] = $in->getString();
		}

		/** @var CommandEnum[] $enums */
		$enums = [];
		for($i = 0, $count = $in->getUnsignedVarInt(); $i < $count; ++$i){
			$enums[] = $enum = $this->getEnum($enumValues, $in);
			//TODO: Bedrock may provide some enums which are not referenced by any command, and can't reasonably be
			//considered "hardcoded". This happens with various Edu command enums, and other enums which are probably
			//intended to be used by commands which aren't present in public releases.
			//We should probably store these somewhere, since we'll need them to be able to correctly re-encode the
			//packet for testing.
			if(isset(self::HARDCODED_ENUM_NAMES[$enum->getName()])){
				$this->hardcodedEnums[] = $enum;
			}
		}

		$chainedSubCommandData = [];
		for($i = 0, $count = $in->getUnsignedVarInt(); $i < $count; ++$i){
			$name = $in->getString();
			$values = [];
			for($j = 0, $valueCount = $in->getUnsignedVarInt(); $j < $valueCount; ++$j){
				$valueName = $chainedSubcommandValueNames[$in->getLShort()];
				$valueType = $in->getLShort();
				$values[] = new ChainedSubCommandValue($valueName, $valueType);
			}
			$chainedSubCommandData[] = new ChainedSubCommandData($name, $values);
		}

		for($i = 0, $count = $in->getUnsignedVarInt(); $i < $count; ++$i){
			$this->commandData[] = $this->getCommandData($enums, $postfixes, $chainedSubCommandData, $in);
		}

		for($i = 0, $count = $in->getUnsignedVarInt(); $i < $count; ++$i){
			$this->softEnums[] = $this->getSoftEnum($in);
		}

		$this->initSoftEnumsInCommandData();

		for($i = 0, $count = $in->getUnsignedVarInt(); $i < $count; ++$i){
			$this->enumConstraints[] = $this->getEnumConstraint($enums, $enumValues, $in);
		}
	}

	/**
	 * Command data is decoded without referencing to any soft enums, as they are decoded afterwards.
	 * So we need to separately add soft enums to the command data
	 */
	protected function initSoftEnumsInCommandData() : void{
		foreach($this->commandData as $datum){
			foreach($datum->getOverloads() as $overload){
				foreach($overload->getParameters() as $parameter){
					if(($parameter->paramType & self::ARG_FLAG_SOFT_ENUM) !== 0){
						$index = $parameter->paramType & 0xffff;
						$parameter->enum = $this->softEnums[$index] ?? null;
						if($parameter->enum === null){
							throw new PacketDecodeException("deserializing $datum->name parameter $parameter->paramName: expected soft enum at $index, but got none");
						}
					}
				}
			}
		}
	}

	/**
	 * @param string[] $enumValueList
	 *
	 * @throws PacketDecodeException
	 * @throws BinaryDataException
	 */
	protected function getEnum(array $enumValueList, PacketSerializer $in) : CommandEnum{
		$enumName = $in->getString();
		$enumValues = [];

		$listSize = count($enumValueList);

		for($i = 0, $count = $in->getUnsignedVarInt(); $i < $count; ++$i){
			$index = $this->getEnumValueIndex($listSize, $in);
			if(!isset($enumValueList[$index])){
				throw new PacketDecodeException("Invalid enum value index $index");
			}
			//Get the enum value from the initial pile of mess
			$enumValues[] = $enumValueList[$index];
		}

		return new CommandEnum($enumName, $enumValues);
	}

	/**
	 * @throws BinaryDataException
	 */
	protected function getSoftEnum(PacketSerializer $in) : CommandEnum{
		$enumName = $in->getString();
		$enumValues = [];

		for($i = 0, $count = $in->getUnsignedVarInt(); $i < $count; ++$i){
			//Get the enum value from the initial pile of mess
			$enumValues[] = $in->getString();
		}

		return new CommandEnum($enumName, $enumValues, true);
	}

	/**
	 * @param int[]       $enumValueMap
	 */
	protected function putEnum(CommandEnum $enum, array $enumValueMap, PacketSerializer $out) : void{
		$out->putString($enum->getName());

		$values = $enum->getValues();
		$out->putUnsignedVarInt(count($values));
		$listSize = count($enumValueMap);
		foreach($values as $value){
			if(!isset($enumValueMap[$value])){
				throw new \LogicException("Enum value '$value' doesn't have a value index");
			}
			$this->putEnumValueIndex($enumValueMap[$value], $listSize, $out);
		}
	}

	protected function putSoftEnum(CommandEnum $enum, PacketSerializer $out) : void{
		$out->putString($enum->getName());

		$values = $enum->getValues();
		$out->putUnsignedVarInt(count($values));
		foreach($values as $value){
			$out->putString($value);
		}
	}

	/**
	 * @throws BinaryDataException
	 */
	protected function getEnumValueIndex(int $valueCount, PacketSerializer $in) : int{
		if($valueCount < 256){
			return $in->getByte();
		}elseif($valueCount < 65536){
			return $in->getLShort();
		}else{
			return $in->getLInt();
		}
	}

	protected function putEnumValueIndex(int $index, int $valueCount, PacketSerializer $out) : void{
		if($valueCount < 256){
			$out->putByte($index);
		}elseif($valueCount < 65536){
			$out->putLShort($index);
		}else{
			$out->putLInt($index);
		}
	}

	/**
	 * @param CommandEnum[] $enums
	 * @param string[]      $enumValues
	 *
	 * @throws PacketDecodeException
	 * @throws BinaryDataException
	 */
	protected function getEnumConstraint(array $enums, array $enumValues, PacketSerializer $in) : CommandEnumConstraint{
		//wtf, what was wrong with an offset inside the enum? :(
		$valueIndex = $in->getLInt();
		if(!isset($enumValues[$valueIndex])){
			throw new PacketDecodeException("Enum constraint refers to unknown enum value index $valueIndex");
		}
		$enumIndex = $in->getLInt();
		if(!isset($enums[$enumIndex])){
			throw new PacketDecodeException("Enum constraint refers to unknown enum index $enumIndex");
		}
		$enum = $enums[$enumIndex];
		$valueOffset = array_search($enumValues[$valueIndex], $enum->getValues(), true);
		if($valueOffset === false){
			throw new PacketDecodeException("Value \"" . $enumValues[$valueIndex] . "\" does not belong to enum \"" . $enum->getName() . "\"");
		}

		$constraintIds = [];
		for($i = 0, $count = $in->getUnsignedVarInt(); $i < $count; ++$i){
			$constraintIds[] = $in->getByte();
		}

		return new CommandEnumConstraint($enum, $valueOffset, $constraintIds);
	}

	/**
	 * @param int[]                 $enumIndexes string enum name -> int index
	 * @param int[]                 $enumValueIndexes string value -> int index
	 */
	protected function putEnumConstraint(CommandEnumConstraint $constraint, array $enumIndexes, array $enumValueIndexes, PacketSerializer $out) : void{
		$out->putLInt($enumValueIndexes[$constraint->getAffectedValue()]);
		$out->putLInt($enumIndexes[$constraint->getEnum()->getName()]);
		$out->putUnsignedVarInt(count($constraint->getConstraints()));
		foreach($constraint->getConstraints() as $v){
			$out->putByte($v);
		}
	}

	/**
	 * @param CommandEnum[]           $enums
	 * @param string[]                $postfixes
	 * @param ChainedSubCommandData[] $allChainedSubCommandData
	 *
	 * @throws PacketDecodeException
	 * @throws BinaryDataException
	 */
	protected function getCommandData(array $enums, array $postfixes, array $allChainedSubCommandData, PacketSerializer $in) : CommandData{
		$name = $in->getString();
		$description = $in->getString();
		$flags = $in->getLShort();
		$permission = $in->getByte();
		$aliases = $enums[$in->getLInt()] ?? null;

		$chainedSubCommandData = [];
		for($i = 0, $count = $in->getUnsignedVarInt(); $i < $count; ++$i){
			$index = $in->getLShort();
			$chainedSubCommandData[] = $allChainedSubCommandData[$index] ?? throw new PacketDecodeException("Unknown chained subcommand data index $index");
		}
		$overloads = [];

		for($overloadIndex = 0, $overloadCount = $in->getUnsignedVarInt(); $overloadIndex < $overloadCount; ++$overloadIndex){
			$parameters = [];
			$isChaining = $in->getBool();
			for($paramIndex = 0, $paramCount = $in->getUnsignedVarInt(); $paramIndex < $paramCount; ++$paramIndex){
				$parameter = new CommandParameter();
				$parameter->paramName = $in->getString();
				$parameter->paramType = $in->getLInt();
				$parameter->isOptional = $in->getBool();
				$parameter->flags = $in->getByte();

				if(($parameter->paramType & self::ARG_FLAG_ENUM) !== 0){
					$index = ($parameter->paramType & 0xffff);
					$parameter->enum = $enums[$index] ?? null;
					if($parameter->enum === null){
						throw new PacketDecodeException("deserializing $name parameter $parameter->paramName: expected enum at $index, but got none");
					}
				}elseif(($parameter->paramType & self::ARG_FLAG_POSTFIX) !== 0){
					$index = ($parameter->paramType & 0xffff);
					$parameter->postfix = $postfixes[$index] ?? null;
					if($parameter->postfix === null){
						throw new PacketDecodeException("deserializing $name parameter $parameter->paramName: expected postfix at $index, but got none");
					}
				}elseif(($parameter->paramType & self::ARG_FLAG_VALID) === 0){
					throw new PacketDecodeException("deserializing $name parameter $parameter->paramName: Invalid parameter type 0x" . dechex($parameter->paramType));
				}

				$parameters[$paramIndex] = $parameter;
			}
			$overloads[$overloadIndex] = new CommandOverload($isChaining, $parameters);
		}

		return new CommandData($name, $description, $flags, $permission, $aliases, $overloads, $chainedSubCommandData);
	}

	/**
	 * @param int[] $enumIndexes string enum name -> int index
	 * @param int[] $softEnumIndexes
	 * @param int[] $postfixIndexes
	 * @param int[] $chainedSubCommandDataIndexes
	 */
	protected function putCommandData(CommandData $data, array $enumIndexes, array $softEnumIndexes, array $postfixIndexes, array $chainedSubCommandDataIndexes, PacketSerializer $out) : void{
		$out->putString($data->name);
		$out->putString($data->description);
		$out->putLShort($data->flags);
		$out->putByte($data->permission);

		if($data->aliases !== null){
			$out->putLInt($enumIndexes[$data->aliases->getName()] ?? -1);
		}else{
			$out->putLInt(-1);
		}

		$out->putUnsignedVarInt(count($data->chainedSubCommandData));
		foreach($data->chainedSubCommandData as $chainedSubCommandData){
			$index = $chainedSubCommandDataIndexes[$chainedSubCommandData->getName()] ??
				throw new \LogicException("Chained subcommand data {$chainedSubCommandData->getName()} does not have an index (this should be impossible)");
			$out->putLShort($index);
		}

		$out->putUnsignedVarInt(count($data->overloads));
		foreach($data->overloads as $overload){
			$out->putBool($overload->isChaining());
			$out->putUnsignedVarInt(count($overload->getParameters()));
			foreach($overload->getParameters() as $parameter){
				$out->putString($parameter->paramName);

				if($parameter->enum !== null){
					if($parameter->enum->isSoft()){
						$type = self::ARG_FLAG_SOFT_ENUM | self::ARG_FLAG_VALID | ($softEnumIndexes[$parameter->enum->getName()] ?? -1);
					}else{
						$type = self::ARG_FLAG_ENUM | self::ARG_FLAG_VALID | ($enumIndexes[$parameter->enum->getName()] ?? -1);
					}
				}elseif($parameter->postfix !== null){
					if(!isset($postfixIndexes[$parameter->postfix])){
						throw new \LogicException("Postfix '$parameter->postfix' not in postfixes array");
					}
					$type = self::ARG_FLAG_POSTFIX | $postfixIndexes[$parameter->postfix];
				}else{
					$type = $parameter->paramType;
				}

				$out->putLInt($type);
				$out->putBool($parameter->isOptional);
				$out->putByte($parameter->flags);
			}
		}
	}

	protected function encodePayload(PacketSerializer $out) : void{
		/**
		 * @var int[] $enumValueIndexes
		 * @phpstan-var array<string, int> $enumValueIndexes
		 */
		$enumValueIndexes = [];
		/**
		 * @var int[] $postfixIndexes
		 * @phpstan-var array<string, int> $postfixIndexes
		 */
		$postfixIndexes = [];

		/**
		 * @var CommandEnum[] $enums
		 * @phpstan-var array<string, CommandEnum> $enums
		 */
		$enums = [];
		/**
		 * @var int[] $enumIndexes
		 * @phpstan-var array<string, int> $enumIndexes
		 */
		$enumIndexes = [];

		/**
		 * @var CommandEnum[] $softEnums
		 * @phpstan-var array<string, CommandEnum> $softEnums
		 */
		$softEnums = [];
		/**
		 * @var int[] $softEnumIndexes
		 * @phpstan-var array<string, int> $softEnumIndexes
		 */
		$softEnumIndexes = [];

		/**
		 * @var ChainedSubCommandData[] $allChainedSubCommandData
		 * @phpstan-var array<string, ChainedSubCommandData> $allChainedSubCommandData
		 */
		$allChainedSubCommandData = [];
		/**
		 * @var int[] $chainedSubCommandDataIndexes
		 * @phpstan-var array<string, int> $chainedSubCommandDataIndexes
		 */
		$chainedSubCommandDataIndexes = [];

		/**
		 * @var int[] $chainedSubCommandValueNameIndexes
		 * @phpstan-var array<string, int> $chainedSubCommandValueNameIndexes
		 */
		$chainedSubCommandValueNameIndexes = [];

		$addEnumFn = static function(CommandEnum $enum) use (
			&$enums, &$softEnums, &$enumIndexes, &$softEnumIndexes, &$enumValueIndexes
		) : void{
			$enumName = $enum->getName();

			if($enum->isSoft()){
				if(!isset($softEnumIndexes[$enumName])){
					$softEnums[$softEnumIndexes[$enumName] = count($softEnumIndexes)] = $enum;
				}
			}else{
				foreach($enum->getValues() as $str){
					$enumValueIndexes[$str] = $enumValueIndexes[$str] ?? count($enumValueIndexes); //latest index
				}
				if(!isset($enumIndexes[$enumName])){
					$enums[$enumIndexes[$enumName] = count($enumIndexes)] = $enum;
				}
			}
		};
		foreach($this->hardcodedEnums as $enum){
			$addEnumFn($enum);
		}
		foreach($this->softEnums as $enum){
			$addEnumFn($enum);
		}
		foreach($this->commandData as $commandData){
			if($commandData->aliases !== null){
				$addEnumFn($commandData->aliases);
			}
			foreach($commandData->overloads as $overload){
				foreach($overload->getParameters() as $parameter){
					if($parameter->enum !== null){
						$addEnumFn($parameter->enum);
					}

					if($parameter->postfix !== null){
						$postfixIndexes[$parameter->postfix] = $postfixIndexes[$parameter->postfix] ?? count($postfixIndexes);
					}
				}
			}
			foreach($commandData->chainedSubCommandData as $chainedSubCommandData){
				if(!isset($allChainedSubCommandData[$chainedSubCommandData->getName()])){
					$allChainedSubCommandData[$chainedSubCommandData->getName()] = $chainedSubCommandData;
					$chainedSubCommandDataIndexes[$chainedSubCommandData->getName()] = count($chainedSubCommandDataIndexes);

					foreach($chainedSubCommandData->getValues() as $value){
						$chainedSubCommandValueNameIndexes[$value->getName()] ??= count($chainedSubCommandValueNameIndexes);
					}
				}
			}
		}

		$out->putUnsignedVarInt(count($enumValueIndexes));
		foreach($enumValueIndexes as $enumValue => $index){
			$out->putString((string) $enumValue); //stupid PHP key casting D:
		}

		$out->putUnsignedVarInt(count($chainedSubCommandValueNameIndexes));
		foreach($chainedSubCommandValueNameIndexes as $chainedSubCommandValueName => $index){
			$out->putString((string) $chainedSubCommandValueName); //stupid PHP key casting D:
		}

		$out->putUnsignedVarInt(count($postfixIndexes));
		foreach($postfixIndexes as $postfix => $index){
			$out->putString((string) $postfix); //stupid PHP key casting D:
		}

		$out->putUnsignedVarInt(count($enums));
		foreach($enums as $enum){
			$this->putEnum($enum, $enumValueIndexes, $out);
		}

		$out->putUnsignedVarInt(count($allChainedSubCommandData));
		foreach($allChainedSubCommandData as $chainedSubCommandData){
			$out->putString($chainedSubCommandData->getName());
			$out->putUnsignedVarInt(count($chainedSubCommandData->getValues()));
			foreach($chainedSubCommandData->getValues() as $value){
				$valueNameIndex = $chainedSubCommandValueNameIndexes[$value->getName()] ??
					throw new \LogicException("Chained subcommand value name index for \"" . $value->getName() . "\" not found (this should never happen)");
				$out->putLShort($valueNameIndex);
				$out->putLShort($value->getType());
			}
		}

		$out->putUnsignedVarInt(count($this->commandData));
		foreach($this->commandData as $data){
			$this->putCommandData($data, $enumIndexes, $softEnumIndexes, $postfixIndexes, $chainedSubCommandDataIndexes, $out);
		}

		$out->putUnsignedVarInt(count($softEnums));
		foreach($softEnums as $enum){
			$this->putSoftEnum($enum, $out);
		}

		$out->putUnsignedVarInt(count($this->enumConstraints));
		foreach($this->enumConstraints as $constraint){
			$this->putEnumConstraint($constraint, $enumIndexes, $enumValueIndexes, $out);
		}
	}

	public function handle(PacketHandlerInterface $handler) : bool{
		return $handler->handleAvailableCommands($this);
	}
}
