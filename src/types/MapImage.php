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

use pocketmine\color\Color;
use pocketmine\network\mcpe\protocol\PacketDecodeException;
use pocketmine\utils\Binary;
use pocketmine\utils\BinaryDataException;
use pocketmine\utils\BinaryStream;
use function count;

final class MapImage{
	//these limits are enforced in the protocol in 1.20.0
	public const MAX_HEIGHT = 128;
	public const MAX_WIDTH = 128;

	private int $width;
	private int $height;
	/**
	 * @var Color[][]
	 * @phpstan-var list<list<Color>>
	 */
	private array $pixels;
	private ?string $encodedPixelCache = null;

	/**
	 * @param Color[][] $pixels
	 * @phpstan-param list<list<Color>> $pixels
	 */
	public function __construct(array $pixels){
		$rowLength = null;
		foreach($pixels as $row){
			if($rowLength === null){
				$rowLength = count($row);
			}elseif(count($row) !== $rowLength){
				throw new \InvalidArgumentException("All rows must have the same number of pixels");
			}
		}
		if($rowLength === null){
			throw new \InvalidArgumentException("No pixels provided");
		}
		if($rowLength > self::MAX_WIDTH){
			throw new \InvalidArgumentException("Image width must be at most " . self::MAX_WIDTH . " pixels wide");
		}
		if(count($pixels) > self::MAX_HEIGHT){
			throw new \InvalidArgumentException("Image height must be at most " . self::MAX_HEIGHT . " pixels tall");
		}
		$this->height = count($pixels);
		$this->width = $rowLength;
		$this->pixels = $pixels;
	}

	public function getWidth() : int{ return $this->width; }

	public function getHeight() : int{ return $this->height; }

	/**
	 * @return Color[][]
	 * @phpstan-return list<list<Color>>
	 */
	public function getPixels() : array{ return $this->pixels; }

	public function encode(BinaryStream $output) : void{
		if($this->encodedPixelCache === null){
			$serializer = new BinaryStream();
			for($y = 0; $y < $this->height; ++$y){
				for($x = 0; $x < $this->width; ++$x){
					//if mojang had any sense this would just be a regular LE int
					$serializer->putUnsignedVarInt(Binary::flipIntEndianness($this->pixels[$y][$x]->toRGBA()));
				}
			}
			$this->encodedPixelCache = $serializer->getBuffer();
		}

		$output->put($this->encodedPixelCache);
	}

	/**
	 * @throws PacketDecodeException
	 * @throws BinaryDataException
	 */
	public static function decode(BinaryStream $input, int $height, int $width) : self{
		if($width > self::MAX_WIDTH){
			throw new PacketDecodeException("Image width must be at most " . self::MAX_WIDTH . " pixels wide");
		}
		if($height > self::MAX_HEIGHT){
			throw new PacketDecodeException("Image height must be at most " . self::MAX_HEIGHT . " pixels tall");
		}
		$pixels = [];

		for($y = 0; $y < $height; ++$y){
			for($x = 0; $x < $width; ++$x){
				$pixels[$y][$x] = Color::fromRGBA(Binary::flipIntEndianness($input->getUnsignedVarInt()));
			}
		}

		return new self($pixels);
	}
}
