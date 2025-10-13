<?php

/*
 *
 *  ____            _        _   __  __ _                  __  __ ____
 * |  _ \ ___   ___| | _____| |_|  \/  (_)_ __   ___      |  \/  |  _ \
 * | |_) / _ \ / __| |/ / _ \ __| |\/| | | '_ \ / _ \_____| |\/| | |_) |
 * |  __/ (_) | (__|   <  __/ |_| |  | | | | | |  __/_____| |  | |  __/
 * |_|   \___/ \___|_|\_\___|\__|_|  |_|_|_| |_|\___|     |_|  |_|_|
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * @author PocketMine Team
 * @link http://www.pocketmine.net/
 *
 *
 */

declare(strict_types=1);

namespace pocketmine\network\mcpe\protocol\serializer;

use pocketmine\network\mcpe\protocol\types\command\CommandData;
use pocketmine\network\mcpe\protocol\types\command\raw\ChainedSubCommandRawData;
use pocketmine\network\mcpe\protocol\types\command\raw\CommandEnumRawData;
use pocketmine\network\mcpe\protocol\types\command\raw\CommandSoftEnumRawData;

/**
 * High-level commands data with all the info properly linked (no nasty offsets).
 */
final class DisassembledAvailableCommandsData{

	/**
	 * @param CommandData[] $commandData
	 * @param string[] $unusedEnumValues
	 * @param string[] $unusedPostfixes
	 * @param CommandEnumRawData[] $unusedHardEnums
	 * @param CommandSoftEnumRawData[] $unusedSoftEnums
	 * @param ChainedSubCommandRawData[] $unusedChainedSubCommandData
	 * @param string[] $unusedChainedSubCommandValues
	 *
	 * @phpstan-param list<CommandData> $commandData
	 * @phpstan-param array<int, string> $unusedEnumValues
	 * @phpstan-param array<int, string> $unusedPostfixes
	 * @phpstan-param array<int, CommandEnumRawData> $unusedHardEnums
	 * @phpstan-param array<int, CommandSoftEnumRawData> $unusedSoftEnums
	 * @phpstan-param array<int, ChainedSubCommandRawData> $unusedChainedSubCommandData
	 * @phpstan-param array<int, string> $unusedChainedSubCommandValues
	 */
	public function __construct(
		public readonly array $commandData,
		public readonly array $unusedEnumValues,
		public readonly array $unusedPostfixes,
		public readonly array $unusedHardEnums,
		public readonly array $unusedSoftEnums,
		public readonly array $unusedChainedSubCommandData,
		public readonly array $unusedChainedSubCommandValues
	){}
}
