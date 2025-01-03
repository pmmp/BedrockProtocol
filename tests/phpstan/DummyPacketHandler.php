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

/**
 * Ensures that PHPStan analyzes PacketHandlerDefaultImplTrait
 * under a normal usage.
 * TODO: Why don't we just have a PacketHandler class in protocol directly???
 */
final class DummyPacketHandler implements PacketHandlerInterface{
	use PacketHandlerDefaultImplTrait;
}
