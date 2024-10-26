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

if(count($argv) < 2){
	fwrite(STDERR, "Required args: path to BedrockData" . PHP_EOL);
	exit(1);
}

passthru(PHP_BINARY . " " . __DIR__ . '/generate-protocol-info.php ' . escapeshellarg($argv[1] . '/protocol_info.json'));
passthru(PHP_BINARY . " " . __DIR__ . '/generate-entity-ids.php ' . escapeshellarg($argv[1] . '/entity_id_map.json'));
passthru(PHP_BINARY . " " . __DIR__ . '/generate-level-sound-ids.php ' . escapeshellarg($argv[1] . '/level_sound_id_map.json'));
passthru(PHP_BINARY . " " . __DIR__ . '/generate-command-parameter-types.php ' . escapeshellarg($argv[1] . '/command_arg_types.json'));
