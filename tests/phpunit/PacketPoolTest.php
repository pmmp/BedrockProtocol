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

namespace phpunit;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use pocketmine\network\mcpe\protocol\ClientboundPacket;
use pocketmine\network\mcpe\protocol\Packet;
use pocketmine\network\mcpe\protocol\PacketPool;
use pocketmine\network\mcpe\protocol\ServerboundPacket;
use function array_filter;
use function count;
use function ucfirst;

final class PacketPoolTest extends TestCase{
	/**
	 * @phpstan-return \Generator<int, array{Packet}, void, void>
	 */
	public static function packetsDataProvider() : \Generator{
		foreach(PacketPool::getInstance()->getAll() as $packet) {
			yield [$packet];
		}
	}

	#[DataProvider('packetsDataProvider')]
	public function testPacketDirectionDesignations(Packet $packet) : void{
		self::assertTrue($packet instanceof ClientboundPacket || $packet instanceof ServerboundPacket, $packet->getName() . " must implement ClientboundPacket, ServerboundPacket, or both\n");
	}

	#[DataProvider('packetsDataProvider')]
	public function testPacketFieldsAccessible(Packet $packet) : void{
		$reflect = new \ReflectionClass($packet);

		$candidates = array_filter($reflect->getProperties(), fn(\ReflectionProperty $p) => !$p->isPublic() && !$p->isStatic() && $p->getDeclaringClass()->getName() === $reflect->getName());
		if(count($candidates) === 0){
			$this->expectNotToPerformAssertions();
			return;
		}

		foreach($candidates as $property){
			$ucfirst = ucfirst($property->getName());
			$accessors = [
				$property->getName(),
				"is" . $ucfirst,
				"has" . $ucfirst,
				"get" . $ucfirst,
			];

			$validAccessors = false;
			foreach($accessors as $accessorName){
				if($reflect->hasMethod($accessorName)){
					$reflectMethod = $reflect->getMethod($accessorName);
					if($reflectMethod->isPublic()){
						$validAccessors = true;
						break;
					}
				}
			}

			self::assertTrue($validAccessors, $reflect->getShortName() . "::$" . $property->getName() . " doesn't have an accessor and is not public");
		}
	}
}
