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
use pmmp\encoding\VarInt;
use pocketmine\network\mcpe\protocol\serializer\AvailableCommandsPacketAssembler;
use pocketmine\network\mcpe\protocol\serializer\AvailableCommandsPacketDisassembler;
use pocketmine\network\mcpe\protocol\serializer\CommonTypes;
use pocketmine\network\mcpe\protocol\types\command\CommandParameterTypes as ArgTypes;
use pocketmine\network\mcpe\protocol\types\command\CommandSoftEnum;
use pocketmine\network\mcpe\protocol\types\command\raw\ChainedSubCommandRawData;
use pocketmine\network\mcpe\protocol\types\command\raw\CommandEnumConstraintRawData;
use pocketmine\network\mcpe\protocol\types\command\raw\CommandEnumRawData;
use pocketmine\network\mcpe\protocol\types\command\raw\CommandRawData;
use function count;

/**
 * Note: It's not recommended to work with this packet directly. It's very complicated, and it's very easy to crash the
 * client if the packet data is incorrect in any way.
 *
 * To assemble a packet for sending from high-level structures, use {@link AvailableCommandsPacketAssembler}.
 * To disassemble a received packet into high-level structures, use {@link AvailableCommandsPacketDisassembler}.
 */
final class AvailableCommandsPacket extends DataPacket implements ClientboundPacket{
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

	/**
	 * @var string[]
	 * @phpstan-var list<string>
	 */
	public array $enumValues = [];
	/**
	 * @var string[]
	 * @phpstan-var list<string>
	 */
	public array $chainedSubCommandValues = [];
	/**
	 * @var string[]
	 * @phpstan-var list<string>
	 */
	public array $postfixes = [];
	/**
	 * @var CommandEnumRawData[]
	 * @phpstan-var list<CommandEnumRawData>
	 */
	public array $enums = [];
	/**
	 * @var ChainedSubCommandRawData[]
	 * @phpstan-var list<ChainedSubCommandRawData>
	 */
	public array $chainedSubCommandData = [];
	/**
	 * @var CommandRawData[]
	 * @phpstan-var list<CommandRawData>
	 */
	public array $commandData = [];
	/**
	 * @var CommandSoftEnum[]
	 * @phpstan-var list<CommandSoftEnum>
	 */
	public array $softEnums = [];
	/**
	 * @var CommandEnumConstraintRawData[]
	 * @phpstan-var list<CommandEnumConstraintRawData>
	 */
	public array $enumConstraints = [];

	/**
	 * @generate-create-func
	 * @param string[]                       $enumValues
	 * @param string[]                       $chainedSubCommandValues
	 * @param string[]                       $postfixes
	 * @param CommandEnumRawData[]           $enums
	 * @param ChainedSubCommandRawData[]     $chainedSubCommandData
	 * @param CommandRawData[]               $commandData
	 * @param CommandSoftEnum[]              $softEnums
	 * @param CommandEnumConstraintRawData[] $enumConstraints
	 * @phpstan-param list<string>                       $enumValues
	 * @phpstan-param list<string>                       $chainedSubCommandValues
	 * @phpstan-param list<string>                       $postfixes
	 * @phpstan-param list<CommandEnumRawData>           $enums
	 * @phpstan-param list<ChainedSubCommandRawData>     $chainedSubCommandData
	 * @phpstan-param list<CommandRawData>               $commandData
	 * @phpstan-param list<CommandSoftEnum>              $softEnums
	 * @phpstan-param list<CommandEnumConstraintRawData> $enumConstraints
	 */
	public static function create(
		array $enumValues,
		array $chainedSubCommandValues,
		array $postfixes,
		array $enums,
		array $chainedSubCommandData,
		array $commandData,
		array $softEnums,
		array $enumConstraints,
	) : self{
		$result = new self;
		$result->enumValues = $enumValues;
		$result->chainedSubCommandValues = $chainedSubCommandValues;
		$result->postfixes = $postfixes;
		$result->enums = $enums;
		$result->chainedSubCommandData = $chainedSubCommandData;
		$result->commandData = $commandData;
		$result->softEnums = $softEnums;
		$result->enumConstraints = $enumConstraints;
		return $result;
	}

	protected function decodePayload(ByteBufferReader $in) : void{
		$this->enumValues = [];
		for($i = 0, $size = VarInt::readUnsignedInt($in); $i < $size; $i++){
			$this->enumValues[] = CommonTypes::getString($in);
		}

		$this->chainedSubCommandValues = [];
		for($i = 0, $size = VarInt::readUnsignedInt($in); $i < $size; $i++){
			$this->chainedSubCommandValues[] = CommonTypes::getString($in);
		}

		$this->postfixes = [];
		for($i = 0, $size = VarInt::readUnsignedInt($in); $i < $size; $i++){
			$this->postfixes[] = CommonTypes::getString($in);
		}

		$this->enums = [];
		for($i = 0, $size = VarInt::readUnsignedInt($in); $i < $size; $i++){
			$this->enums[] = CommandEnumRawData::read($in);
		}

		$this->chainedSubCommandData = [];
		for($i = 0, $size = VarInt::readUnsignedInt($in); $i < $size; $i++){
			$this->chainedSubCommandData[] = ChainedSubCommandRawData::read($in);
		}

		$this->commandData = [];
		for($i = 0, $size = VarInt::readUnsignedInt($in); $i < $size; $i++){
			$this->commandData[] = CommandRawData::read($in);
		}

		$this->softEnums = [];
		for($i = 0, $size = VarInt::readUnsignedInt($in); $i < $size; $i++){
			$this->softEnums[] = CommandSoftEnum::read($in);
		}

		$this->enumConstraints = [];
		for($i = 0, $size = VarInt::readUnsignedInt($in); $i < $size; $i++){
			$this->enumConstraints[] = CommandEnumConstraintRawData::read($in);
		}
	}

	protected function encodePayload(ByteBufferWriter $out) : void{
		VarInt::writeUnsignedInt($out, count($this->enumValues));
		foreach($this->enumValues as $value){
			CommonTypes::putString($out, $value);
		}

		VarInt::writeUnsignedInt($out, count($this->chainedSubCommandValues));
		foreach($this->chainedSubCommandValues as $value){
			CommonTypes::putString($out, $value);
		}

		VarInt::writeUnsignedInt($out, count($this->postfixes));
		foreach($this->postfixes as $postfix){
			CommonTypes::putString($out, $postfix);
		}

		VarInt::writeUnsignedInt($out, count($this->enums));
		foreach($this->enums as $enum){
			$enum->write($out);
		}

		VarInt::writeUnsignedInt($out, count($this->chainedSubCommandData));
		foreach($this->chainedSubCommandData as $data){
			$data->write($out);
		}

		VarInt::writeUnsignedInt($out, count($this->commandData));
		foreach($this->commandData as $data){
			$data->write($out);
		}

		VarInt::writeUnsignedInt($out, count($this->softEnums));
		foreach($this->softEnums as $softEnum){
			$softEnum->write($out);
		}

		VarInt::writeUnsignedInt($out, count($this->enumConstraints));
		foreach($this->enumConstraints as $constraint){
			$constraint->write($out);
		}
	}

	public function handle(PacketHandlerInterface $handler) : bool{
		return $handler->handleAvailableCommands($this);
	}
}
