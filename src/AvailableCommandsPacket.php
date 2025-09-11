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

use pmmp\encoding\Byte;
use pmmp\encoding\ByteBufferReader;
use pmmp\encoding\ByteBufferWriter;
use pmmp\encoding\DataDecodeException;
use pmmp\encoding\LE;
use pmmp\encoding\VarInt;
use pocketmine\network\mcpe\protocol\serializer\CommonTypes;
use pocketmine\network\mcpe\protocol\types\command\ChainedSubCommandData;
use pocketmine\network\mcpe\protocol\types\command\ChainedSubCommandValue;
use pocketmine\network\mcpe\protocol\types\command\CommandData;
use pocketmine\network\mcpe\protocol\types\command\CommandEnum;
use pocketmine\network\mcpe\protocol\types\command\CommandEnumConstraint;
use pocketmine\network\mcpe\protocol\types\command\CommandOverload;
use pocketmine\network\mcpe\protocol\types\command\CommandParameter;
use pocketmine\network\mcpe\protocol\types\command\CommandParameterTypes as ArgTypes;
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

	protected function decodePayload(ByteBufferReader $in) : void{
		/** @var string[] $enumValues */
		$enumValues = [];
		for($i = 0, $enumValuesCount = VarInt::readUnsignedInt($in); $i < $enumValuesCount; ++$i){
			$enumValues[] = CommonTypes::getString($in);
		}

		/** @var string[] $chainedSubcommandValueNames */
		$chainedSubcommandValueNames = [];
		for($i = 0, $count = VarInt::readUnsignedInt($in); $i < $count; ++$i){
			$chainedSubcommandValueNames[] = CommonTypes::getString($in);
		}

		/** @var string[] $postfixes */
		$postfixes = [];
		for($i = 0, $count = VarInt::readUnsignedInt($in); $i < $count; ++$i){
			$postfixes[] = CommonTypes::getString($in);
		}

		/** @var CommandEnum[] $enums */
		$enums = [];
		for($i = 0, $count = VarInt::readUnsignedInt($in); $i < $count; ++$i){
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
		for($i = 0, $count = VarInt::readUnsignedInt($in); $i < $count; ++$i){
			$name = CommonTypes::getString($in);
			$values = [];
			for($j = 0, $valueCount = VarInt::readUnsignedInt($in); $j < $valueCount; ++$j){
				$valueName = $chainedSubcommandValueNames[LE::readUnsignedShort($in)];
				$valueType = LE::readUnsignedShort($in);
				$values[] = new ChainedSubCommandValue($valueName, $valueType);
			}
			$chainedSubCommandData[] = new ChainedSubCommandData($name, $values);
		}

		for($i = 0, $count = VarInt::readUnsignedInt($in); $i < $count; ++$i){
			$this->commandData[] = $this->getCommandData($enums, $postfixes, $chainedSubCommandData, $in);
		}

		for($i = 0, $count = VarInt::readUnsignedInt($in); $i < $count; ++$i){
			$this->softEnums[] = $this->getSoftEnum($in);
		}

		$this->initSoftEnumsInCommandData();

		for($i = 0, $count = VarInt::readUnsignedInt($in); $i < $count; ++$i){
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
	 * @throws DataDecodeException
	 */
	protected function getEnum(array $enumValueList, ByteBufferReader $in) : CommandEnum{
		$enumName = CommonTypes::getString($in);
		$enumValues = [];

		$listSize = count($enumValueList);

		for($i = 0, $count = VarInt::readUnsignedInt($in); $i < $count; ++$i){
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
	 * @throws DataDecodeException
	 */
	protected function getSoftEnum(ByteBufferReader $in) : CommandEnum{
		$enumName = CommonTypes::getString($in);
		$enumValues = [];

		for($i = 0, $count = VarInt::readUnsignedInt($in); $i < $count; ++$i){
			//Get the enum value from the initial pile of mess
			$enumValues[] = CommonTypes::getString($in);
		}

		return new CommandEnum($enumName, $enumValues, true);
	}

	/**
	 * @param int[]       $enumValueMap
	 */
	protected function putEnum(CommandEnum $enum, array $enumValueMap, ByteBufferWriter $out) : void{
		CommonTypes::putString($out, $enum->getName());

		$values = $enum->getValues();
		VarInt::writeUnsignedInt($out, count($values));
		$listSize = count($enumValueMap);
		foreach($values as $value){
			if(!isset($enumValueMap[$value])){
				throw new \LogicException("Enum value '$value' doesn't have a value index");
			}
			$this->putEnumValueIndex($enumValueMap[$value], $listSize, $out);
		}
	}

	protected function putSoftEnum(CommandEnum $enum, ByteBufferWriter $out) : void{
		CommonTypes::putString($out, $enum->getName());

		$values = $enum->getValues();
		VarInt::writeUnsignedInt($out, count($values));
		foreach($values as $value){
			CommonTypes::putString($out, $value);
		}
	}

	/**
	 * @throws DataDecodeException
	 */
	protected function getEnumValueIndex(int $valueCount, ByteBufferReader $in) : int{
		if($valueCount < 256){
			return Byte::readUnsigned($in);
		}elseif($valueCount < 65536){
			return LE::readUnsignedShort($in);
		}else{
			return LE::readUnsignedInt($in);
		}
	}

	protected function putEnumValueIndex(int $index, int $valueCount, ByteBufferWriter $out) : void{
		if($valueCount < 256){
			Byte::writeUnsigned($out, $index);
		}elseif($valueCount < 65536){
			LE::writeUnsignedShort($out, $index);
		}else{
			LE::writeUnsignedInt($out, $index);
		}
	}

	/**
	 * @param CommandEnum[] $enums
	 * @param string[]      $enumValues
	 *
	 * @throws PacketDecodeException
	 * @throws DataDecodeException
	 */
	protected function getEnumConstraint(array $enums, array $enumValues, ByteBufferReader $in) : CommandEnumConstraint{
		//wtf, what was wrong with an offset inside the enum? :(
		$valueIndex = LE::readUnsignedInt($in);
		if(!isset($enumValues[$valueIndex])){
			throw new PacketDecodeException("Enum constraint refers to unknown enum value index $valueIndex");
		}
		$enumIndex = LE::readUnsignedInt($in);
		if(!isset($enums[$enumIndex])){
			throw new PacketDecodeException("Enum constraint refers to unknown enum index $enumIndex");
		}
		$enum = $enums[$enumIndex];
		$valueOffset = array_search($enumValues[$valueIndex], $enum->getValues(), true);
		if($valueOffset === false){
			throw new PacketDecodeException("Value \"" . $enumValues[$valueIndex] . "\" does not belong to enum \"" . $enum->getName() . "\"");
		}

		$constraintIds = [];
		for($i = 0, $count = VarInt::readUnsignedInt($in); $i < $count; ++$i){
			$constraintIds[] = Byte::readUnsigned($in);
		}

		return new CommandEnumConstraint($enum, $valueOffset, $constraintIds);
	}

	/**
	 * @param int[]                 $enumIndexes string enum name -> int index
	 * @param int[]                 $enumValueIndexes string value -> int index
	 */
	protected function putEnumConstraint(CommandEnumConstraint $constraint, array $enumIndexes, array $enumValueIndexes, ByteBufferWriter $out) : void{
		LE::writeUnsignedInt($out, $enumValueIndexes[$constraint->getAffectedValue()]);
		LE::writeUnsignedInt($out, $enumIndexes[$constraint->getEnum()->getName()]);
		VarInt::writeUnsignedInt($out, count($constraint->getConstraints()));
		foreach($constraint->getConstraints() as $v){
			Byte::writeUnsigned($out, $v);
		}
	}

	/**
	 * @param CommandEnum[]           $enums
	 * @param string[]                $postfixes
	 * @param ChainedSubCommandData[] $allChainedSubCommandData
	 *
	 * @throws PacketDecodeException
	 * @throws DataDecodeException
	 */
	protected function getCommandData(array $enums, array $postfixes, array $allChainedSubCommandData, ByteBufferReader $in) : CommandData{
		$name = CommonTypes::getString($in);
		$description = CommonTypes::getString($in);
		$flags = LE::readUnsignedShort($in);
		$permission = Byte::readUnsigned($in);
		$aliases = $enums[LE::readSignedInt($in)] ?? null;

		$chainedSubCommandData = [];
		for($i = 0, $count = VarInt::readUnsignedInt($in); $i < $count; ++$i){
			$index = LE::readUnsignedShort($in);
			$chainedSubCommandData[] = $allChainedSubCommandData[$index] ?? throw new PacketDecodeException("Unknown chained subcommand data index $index");
		}
		$overloads = [];

		for($overloadIndex = 0, $overloadCount = VarInt::readUnsignedInt($in); $overloadIndex < $overloadCount; ++$overloadIndex){
			$parameters = [];
			$isChaining = CommonTypes::getBool($in);
			for($paramIndex = 0, $paramCount = VarInt::readUnsignedInt($in); $paramIndex < $paramCount; ++$paramIndex){
				$parameter = new CommandParameter();
				$parameter->paramName = CommonTypes::getString($in);
				$parameter->paramType = LE::readUnsignedInt($in);
				$parameter->isOptional = CommonTypes::getBool($in);
				$parameter->flags = Byte::readUnsigned($in);

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
	protected function putCommandData(CommandData $data, array $enumIndexes, array $softEnumIndexes, array $postfixIndexes, array $chainedSubCommandDataIndexes, ByteBufferWriter $out) : void{
		CommonTypes::putString($out, $data->name);
		CommonTypes::putString($out, $data->description);
		LE::writeUnsignedShort($out, $data->flags);
		Byte::writeUnsigned($out, $data->permission);

		if($data->aliases !== null){
			LE::writeSignedInt($out, $enumIndexes[$data->aliases->getName()] ?? -1);
		}else{
			LE::writeSignedInt($out, -1);
		}

		VarInt::writeUnsignedInt($out, count($data->chainedSubCommandData));
		foreach($data->chainedSubCommandData as $chainedSubCommandData){
			$index = $chainedSubCommandDataIndexes[$chainedSubCommandData->getName()] ??
				throw new \LogicException("Chained subcommand data {$chainedSubCommandData->getName()} does not have an index (this should be impossible)");
			LE::writeUnsignedShort($out, $index);
		}

		VarInt::writeUnsignedInt($out, count($data->overloads));
		foreach($data->overloads as $overload){
			CommonTypes::putBool($out, $overload->isChaining());
			VarInt::writeUnsignedInt($out, count($overload->getParameters()));
			foreach($overload->getParameters() as $parameter){
				CommonTypes::putString($out, $parameter->paramName);

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

				LE::writeUnsignedInt($out, $type);
				CommonTypes::putBool($out, $parameter->isOptional);
				Byte::writeUnsigned($out, $parameter->flags);
			}
		}
	}

	protected function encodePayload(ByteBufferWriter $out) : void{
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

		VarInt::writeUnsignedInt($out, count($enumValueIndexes));
		foreach($enumValueIndexes as $enumValue => $index){
			CommonTypes::putString($out, (string) $enumValue); //stupid PHP key casting D:
		}

		VarInt::writeUnsignedInt($out, count($chainedSubCommandValueNameIndexes));
		foreach($chainedSubCommandValueNameIndexes as $chainedSubCommandValueName => $index){
			CommonTypes::putString($out, (string) $chainedSubCommandValueName); //stupid PHP key casting D:
		}

		VarInt::writeUnsignedInt($out, count($postfixIndexes));
		foreach($postfixIndexes as $postfix => $index){
			CommonTypes::putString($out, (string) $postfix); //stupid PHP key casting D:
		}

		VarInt::writeUnsignedInt($out, count($enums));
		foreach($enums as $enum){
			$this->putEnum($enum, $enumValueIndexes, $out);
		}

		VarInt::writeUnsignedInt($out, count($allChainedSubCommandData));
		foreach($allChainedSubCommandData as $chainedSubCommandData){
			CommonTypes::putString($out, $chainedSubCommandData->getName());
			VarInt::writeUnsignedInt($out, count($chainedSubCommandData->getValues()));
			foreach($chainedSubCommandData->getValues() as $value){
				$valueNameIndex = $chainedSubCommandValueNameIndexes[$value->getName()] ??
					throw new \LogicException("Chained subcommand value name index for \"" . $value->getName() . "\" not found (this should never happen)");
				LE::writeUnsignedShort($out, $valueNameIndex);
				LE::writeUnsignedShort($out, $value->getType());
			}
		}

		VarInt::writeUnsignedInt($out, count($this->commandData));
		foreach($this->commandData as $data){
			$this->putCommandData($data, $enumIndexes, $softEnumIndexes, $postfixIndexes, $chainedSubCommandDataIndexes, $out);
		}

		VarInt::writeUnsignedInt($out, count($softEnums));
		foreach($softEnums as $enum){
			$this->putSoftEnum($enum, $out);
		}

		VarInt::writeUnsignedInt($out, count($this->enumConstraints));
		foreach($this->enumConstraints as $constraint){
			$this->putEnumConstraint($constraint, $enumIndexes, $enumValueIndexes, $out);
		}
	}

	public function handle(PacketHandlerInterface $handler) : bool{
		return $handler->handleAvailableCommands($this);
	}
}
