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

/**
 * Model class for JsonMapper which describes the RFC7519 standard fields in a JWT. Any of these fields might not be
 * provided.
 */
class JwtBodyRfc7519{
	public string $iss;
	public string $sub;
	/** @var string|string[] */
	public $aud;
	public int $exp;
	public int $nbf;
	public int $iat;
	public string $jti;
}
