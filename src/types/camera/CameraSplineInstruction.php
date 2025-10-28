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

namespace pocketmine\network\mcpe\protocol\types\camera;

use pmmp\encoding\Byte;
use pmmp\encoding\ByteBufferReader;
use pmmp\encoding\ByteBufferWriter;
use pmmp\encoding\LE;
use pmmp\encoding\VarInt;
use pocketmine\math\Vector2;
use pocketmine\math\Vector3;
use pocketmine\network\mcpe\protocol\serializer\CommonTypes;
use function count;

final class CameraSplineInstruction{

	/**
	 * @see CameraSetInstructionEaseType
	 *
	 * @param Vector3[] $curve
	 * @param Vector2[] $progressKeyFrames
	 * @param CameraRotationOption[] $rotationOptions
	 */
	public function __construct(
		private float $totalTime,
		private int $easeType,
		private array $curve,
		private array $progressKeyFrames,
		private array $rotationOptions,
	){}

	public function getTotalTime() : float{ return $this->totalTime; }

	/**
	 * @see CameraSetInstructionEaseType
	 */
	public function getEaseType() : int{ return $this->easeType; }

	/**
	 * @return Vector3[]
	 */
	public function getCurve() : array{ return $this->curve; }

	/**
	 * @return Vector2[]
	 */
	public function getProgressKeyFrames() : array{ return $this->progressKeyFrames; }

	/**
	 * @return CameraRotationOption[]
	 */
	public function getRotationOptions() : array{ return $this->rotationOptions; }

	public static function read(ByteBufferReader $in) : self{
		$totalTime = LE::readFloat($in);
		$easeType = Byte::readUnsigned($in);

		$curve = [];
		$curveCount = VarInt::readUnsignedInt($in);
		for($i = 0; $i < $curveCount; ++$i){
			$curve[] = CommonTypes::getVector3($in);
		}

		$progressKeyFrames = [];
		$progressKeyFrameCount = VarInt::readUnsignedInt($in);
		for($i = 0; $i < $progressKeyFrameCount; ++$i){
			$progressKeyFrames[] = CommonTypes::getVector2($in);
		}

		$rotationOptions = [];
		$rotationOptionCount = VarInt::readUnsignedInt($in);
		for($i = 0; $i < $rotationOptionCount; ++$i){
			$rotationOptions[] = CameraRotationOption::read($in);
		}

		return new self($totalTime, $easeType, $curve, $progressKeyFrames, $rotationOptions);
	}

	public function write(ByteBufferWriter $out) : void{
		LE::writeFloat($out, $this->totalTime);
		Byte::writeUnsigned($out, $this->easeType);

		VarInt::writeUnsignedInt($out, count($this->curve));
		foreach($this->curve as $point){
			CommonTypes::putVector3($out, $point);
		}

		VarInt::writeUnsignedInt($out, count($this->progressKeyFrames));
		foreach($this->progressKeyFrames as $keyFrame){
			CommonTypes::putVector2($out, $keyFrame);
		}

		VarInt::writeUnsignedInt($out, count($this->rotationOptions));
		foreach($this->rotationOptions as $option){
			$option->write($out);
		}
	}
}
