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

namespace pocketmine\network\mcpe\protocol\tools\generate_create_static_methods;

use function array_map;
use function array_slice;
use function basename;
use function class_exists;
use function count;
use function dirname;
use function file_get_contents;
use function file_put_contents;
use function implode;
use function max;
use function preg_match;
use function preg_split;
use function str_pad;
use function str_repeat;
use function strlen;
use function substr;
use function trim;

require dirname(__DIR__) . '/vendor/autoload.php';

function generateCreateFunction(\ReflectionClass $reflect, int $indentLevel, int $modifiers) : array{
	$properties = [];
	$paramTags = [];
	$longestParamType = 0;
	$phpstanParamTags = [];
	$longestPhpstanParamType = 0;
	foreach($reflect->getProperties() as $property){
		if($property->getDeclaringClass()->getName() !== $reflect->getName()){
			continue;
		}
		$properties[$property->getName()] = $property;
		if(($phpDoc = $property->getDocComment()) !== false && preg_match('/@var ([A-Za-z\d\[\]\\\\]+)/', $phpDoc, $matches) === 1){
			$paramTags[] = [$matches[1], $property->getName()];
			$longestParamType = max($longestParamType, strlen($matches[1]));
		}
		if(($phpDoc = $property->getDocComment()) !== false && preg_match('/@phpstan-var ([A-Za-z\d\[\]\\\\<>:\*\(\)\$ ,]+)/', substr($phpDoc, 3, -2), $matches) === 1){
			$matches[1] = trim($matches[1]);
			$phpstanParamTags[] = [$matches[1], $property->getName()];
			$longestPhpstanParamType = max($longestPhpstanParamType, strlen($matches[1]));
		}
	}

	$lines = [];
	$lines[] = "/**";
	$lines[] = " * @generate-create-func";
	foreach($paramTags as $paramTag){
		$lines[] = " * @param " . str_pad($paramTag[0], $longestParamType, " ", STR_PAD_RIGHT) . " $" . $paramTag[1];
	}
	foreach($phpstanParamTags as $paramTag){
		$lines[] = " * @phpstan-param " . str_pad($paramTag[0], $longestPhpstanParamType, " ", STR_PAD_RIGHT) . " $" . $paramTag[1];
	}
	$lines[] = " */";

	$visibilityStr = match(true){
		($modifiers & \ReflectionMethod::IS_PUBLIC) !== 0 => "public",
		($modifiers & \ReflectionMethod::IS_PRIVATE) !== 0 => "private",
		($modifiers & \ReflectionMethod::IS_PROTECTED) !== 0 => "protected",
		default => throw new \InvalidArgumentException("Visibility must be a ReflectionMethod visibility constant")
	};
	$funcStart = "$visibilityStr static function create(";
	$funcEnd = ") : self{";
	$params = [];
	foreach($properties as $name => $reflectProperty){
		$stringType = "";
		$propertyType = $reflectProperty->getType();

		//this will generate FQNs, we leave them alone and let php-cs-fixer deal with them
		if($propertyType instanceof \ReflectionNamedType){
			$stringType = ($propertyType->allowsNull() ? "?" : "") . ($propertyType->isBuiltin() ? "" : "\\") . $propertyType->getName();
		}elseif($propertyType instanceof \ReflectionUnionType){
			$stringType = implode("|", array_map(fn(\ReflectionNamedType $subType) => ($subType->isBuiltin() ? "" : "\\") . $subType->getName(), $propertyType->getTypes()));
		}

		$params[] = ($stringType !== "" ? "$stringType " : "") . "\$$name";
	}
	if(count($params) <= 6){
		$lines[] = $funcStart . implode(", ", $params) . $funcEnd;
	}else{
		$lines[] = $funcStart;
		foreach($params as $param){
			$lines[] = "\t$param,";
		}
		$lines[] = $funcEnd;
	}
	if(count($params) > 0){
		$lines[] = "\t\$result = new self;";
		foreach($properties as $name => $reflectProperty){
			$lines[] = "\t\$result->$name = \$$name;";
		}
		$lines[] = "\treturn \$result;";
	}else{
		$lines[] = "\treturn new self;";
	}
	$lines[] = "}";

	return array_map(fn(string $line) => str_repeat("\t", $indentLevel) . $line, $lines);
}

foreach(new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator(dirname(__DIR__) . '/src', \FilesystemIterator::SKIP_DOTS | \FilesystemIterator::CURRENT_AS_PATHNAME)) as $file){
	if(substr($file, -4) !== ".php"){
		continue;
	}
	$contents = file_get_contents($file);
	if($contents === false){
		throw new \RuntimeException("Failed to get contents of $file");
	}

	if(preg_match("/(*ANYCRLF)^namespace (.+);$/m", $contents, $matches) !== 1 || preg_match('/(*ANYCRLF)^((final|abstract)\s+)?class /m', $contents) !== 1){
		continue;
	}
	$shortClassName = basename($file, ".php");
	$className = $matches[1] . "\\" . $shortClassName;
	if(!class_exists($className)){
		continue;
	}
	$reflect = new \ReflectionClass($className);
	echo "Found class " . $reflect->getName() . ": ";
	if(!$reflect->hasMethod("create")){
		echo "skipped: no create() found\n";
		continue;
	}
	$createReflect = $reflect->getMethod("create");
	if($createReflect->getDeclaringClass()->getName() !== $reflect->getName()){
		echo "skipped: create() declared by parent class\n";
		continue;
	}

	$docComment = $createReflect->getDocComment();
	if($docComment === false || preg_match('/@generate-create-func\s/', $docComment) !== 1){
		echo "skipped: @generate-create-func not found in create() doc comment\n";
		continue;
	}

	$lines = preg_split("/(*ANYCRLF)\n/", $contents);
	$docCommentLines = preg_split("/(*ANYCRLF)\n/", $docComment);
	$beforeLines = array_slice($lines, 0, $createReflect->getStartLine() - 1 - count($docCommentLines));
	$afterLines = array_slice($lines, $createReflect->getEndLine());
	file_put_contents($file, implode("\n", $beforeLines) . "\n" . implode("\n", generateCreateFunction($reflect, 1, $createReflect->getModifiers())) . "\n" . implode("\n", $afterLines));
	echo "successfully patched class\n";
}
