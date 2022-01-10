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

interface Packet{

	public function pid() : int;

	public function getName() : string;

	public function canBeSentBeforeLogin() : bool;

	/**
	 * @throws PacketDecodeException
	 */
	public function decode(PacketSerializer $in) : void;

	public function encode(PacketSerializer $out) : void;

	/**
	 * Performs handling for this packet. Usually you'll want an appropriately named method in the session handler for
	 * this.
	 *
	 * This method returns a bool to indicate whether the packet was handled or not. If the packet was unhandled, a
	 * debug message will be logged with a hexdump of the packet.
	 *
	 * Typically this method returns the return value of the handler in the supplied PacketHandler. See other packets
	 * for examples how to implement this.
	 *
	 * @return bool true if the packet was handled successfully, false if not.
	 * @throws PacketDecodeException if broken data was found in the packet
	 */
	public function handle(PacketHandlerInterface $handler) : bool;
}
