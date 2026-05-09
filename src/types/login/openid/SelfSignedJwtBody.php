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

namespace pocketmine\network\mcpe\protocol\types\login\openid;

use pocketmine\network\mcpe\protocol\types\login\JwtBodyRfc7519;

/**
 * Mostly the same as the xbox token but with some weird differences
 */
final class SelfSignedJwtBody extends JwtBodyRfc7519{

	/** @required */
	public string $cpk; // the public key that was used to sign the "client properties" token

	/** @required */
	public string $leguuid; // the client's chosen UUID

	/** @required */
	public string $xname; // the player's chosen name, nothing to do with Xbox but shares the same property name

	/** @required */
	public string $mid; // the player's Minecraft ID, identifying the player in Minecraft's PlayFab namespace

	public int $ap; // ??

	//The following are not required for self-signed authentication, but seem to be present as empty strings in a
	//self-signed token for some reason

	public string $nid;
	public string $nname;

	public string $pid;
	public string $pname;

	public string $xid;

}
