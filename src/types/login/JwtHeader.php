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

namespace pocketmine\network\mcpe\protocol\types\login;

final class JwtHeader{
	/** @required */
	public string $alg;
	/** @required */
	public string $x5u;

	/**
	 * As of 2023-03-29, this field suddenly started appearing in JWTs returned by the Mojang authentication API.
	 * It's unclear whether this was intended, but it is part of the JWT spec, so it's not a problem to accept it.
	 */
	public string $x5t;
}
