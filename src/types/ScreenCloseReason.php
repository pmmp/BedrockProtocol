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

namespace pocketmine\network\mcpe\protocol\types;

/**
 * @see ServerboundDataDrivenScreenClosedPacket
 */
final class ScreenCloseReason{
	public const PROGRAMMATIC_CLOSE = "programmaticclose";
	public const PROGRAMMATIC_CLOSE_ALL = "programmaticcloseall";
	public const CLIENT_CANCELED = "clientcanceled";
	public const USER_BUSY = "userbusy";
	public const INVALID_FORM = "invalidform";
}
