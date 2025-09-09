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

namespace pocketmine\network\mcpe\protocol\types\login\openid\api;

/**
 * Model class for https://authorization.franchise.minecraft-services.net/.well-known/keys JSON data for JsonMapper
 */
final class AuthServiceKey{
	/** @required */
	public string $kty;

	/** @required */
	public string $use;

	/** @required */
	public string $kid;

	/** @required */
	public string $x5t;

	/** @required */
	public string $n;

	/** @required */
	public string $e;
}
