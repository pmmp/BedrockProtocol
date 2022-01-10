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
 * Model class for LoginPacket JSON data for JsonMapper
 */
final class ClientDataAnimationFrame{

	/** @required */
	public int $ImageHeight;

	/** @required */
	public int $ImageWidth;

	/** @required */
	public float $Frames;

	/** @required */
	public int $Type;

	/** @required */
	public string $Image;

	/** @required */
	public int $AnimationExpression;
}
