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

use pocketmine\network\mcpe\protocol\PlayerAuthInputPacket;

/**
 * These flags are used in PlayerAuthInputPacket's inputFlags field.
 * The flags should be written as
 * `flags |= (1 << flag)`
 * and read as
 * `(flags & (1 << flag)) !== 0`
 *
 * @see PlayerAuthInputPacket
 */
final class PlayerAuthInputFlags{
	/** Pressing the "fly up" key when using touch. */
	public const ASCEND = 0;
	/** Pressing the "fly down" key when using touch. */
	public const DESCEND = 1;
	/** Pressing (and optionally holding) the jump key (while not flying). */
	public const NORTH_JUMP = 2;
	/** Pressing (and optionally holding) the jump key (including while flying). */
	public const JUMP_DOWN = 3;
	/** Pressing (and optionally holding) the sprint key (typically the CTRL key). Does not include double-pressing the forward key. */
	public const SPRINT_DOWN = 4;
	/** Pressing (and optionally holding) the fly button ONCE when in flight mode when using touch. This has no obvious use. */
	public const CHANGE_HEIGHT = 5;
	/** Pressing (and optionally holding) the jump key (including while flying), and also auto-jumping. */
	public const JUMPING = 6;
	/** Auto-swimming upwards while pressing forwards with auto-jump enabled. */
	public const AUTO_JUMPING_IN_WATER = 7;
	/** Sneaking, and pressing the "fly down" key or "sneak" key (including while flying). */
	public const SNEAKING = 8;
	/** Pressing (and optionally holding) the sneak key (including while flying). This includes when the sneak button is toggled ON with touch controls. */
	public const SNEAK_DOWN = 9;
	/** Pressing the forward key (typically W on keyboard). */
	public const UP = 10;
	/** Pressing the backward key (typically S on keyboard). */
	public const DOWN = 11;
	/** Pressing the left key (typically A on keyboard). */
	public const LEFT = 12;
	/** Pressing the right key (typically D on keyboard). */
	public const RIGHT = 13;
	/** Pressing the ↖ key on touch. */
	public const UP_LEFT = 14;
	/** Pressing the ↗ key on touch. */
	public const UP_RIGHT = 15;
	/** Client wants to go upwards. Sent when Ascend or Jump is pressed, irrespective of whether flight is enabled. */
	public const WANT_UP = 16;
	/** Client wants to go downwards. Sent when Descend or Sneak is pressed, irrespective of whether flight is enabled. */
	public const WANT_DOWN = 17;
	/** Same as "want up" but slow. Only usable with controllers at the time of writing. Triggered by pressing the right joystick by default. */
	public const WANT_DOWN_SLOW = 18;
	/** Same as "want down" but slow. Only usable with controllers at the time of writing. Not bound to any control by default. */
	public const WANT_UP_SLOW = 19;
	/** Unclear usage, during testing it was only seen in conjunction with SPRINT_DOWN. NOT sent while actually sprinting. */
	public const SPRINTING = 20;
	/** Ascending scaffolding. Note that this is NOT sent when climbing ladders. */
	public const ASCEND_BLOCK = 21;
	/** Descending scaffolding. */
	public const DESCEND_BLOCK = 22;
	/** Toggling the sneak button on touch when the button enters the "enabled" state. */
	public const SNEAK_TOGGLE_DOWN = 23;
	/** Unclear use. Sent continually on touch controls, irrespective of whether the player is actually sneaking or not. */
	public const PERSIST_SNEAK = 24;
	public const START_SPRINTING = 25;
	public const STOP_SPRINTING = 26;
	public const START_SNEAKING = 27;
	public const STOP_SNEAKING = 28;
	public const START_SWIMMING = 29;
	public const STOP_SWIMMING = 30;
	/** Initiating a new jump. Sent every time the client leaves the ground due to jumping, including auto jumps. */
	public const START_JUMPING = 31;
	public const START_GLIDING = 32;
	public const STOP_GLIDING = 33;
	public const PERFORM_ITEM_INTERACTION = 34;
	public const PERFORM_BLOCK_ACTIONS = 35;
	public const PERFORM_ITEM_STACK_REQUEST = 36;
	public const HANDLED_TELEPORT = 37;
	public const EMOTING = 38;
	/** Left-clicking the air. In vanilla, this generates an ATTACK_NODAMAGE sound and does nothing else. */
	public const MISSED_SWING = 39;
	public const START_CRAWLING = 40;
	public const STOP_CRAWLING = 41;
	public const START_FLYING = 42;
	public const STOP_FLYING = 43;
	public const ACK_ACTOR_DATA = 44;
	public const IN_CLIENT_PREDICTED_VEHICLE = 45;
	public const PADDLING_LEFT = 46;
	public const PADDLING_RIGHT = 47;

}
